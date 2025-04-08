<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\EshopEnterprise\Internal\Framework\Module\Configuration\Bridge\ShopConfigurationGeneratorBridgeInterface;

/**
 * @inheritdoc
 */
class ShopMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ShopMain
{
    /**
     * Return template name for new shop template
     * to create new Shop or if creation could not be done
     * due to multishop error.
     *
     * @return string
     */
    protected function renderNewShop()
    {
        $templateName = parent::renderNewShop();
        $shopId = $this->getEditObjectId();

        if (!$shopId || $this->_aViewData['sMandateWarning'] || $this->_aViewData['sMaxShopWarning']) {
            $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
            $shopList = oxNew(\OxidEsales\Eshop\Application\Model\ShopList::class);
            $shopList->getAll();
            $this->_aViewData["shopids"] = $shopList;

            $newShopId = $shop->getNewShopId();

            if ($newShopId !== false) {
                $this->_aViewData["newshopid"] = $newShopId;
                $this->_aViewData["oxid"] = -1;
            } else {
                $this->_aViewData['sMaxShopWarning'] = true;
            }

            $templateName = "shop_main_new.tpl";
        }

        return $templateName;
    }

    /**
     * Check user rights and change userId if need.
     *
     * @param \OxidEsales\Eshop\Application\Model\User $user
     * @param string $shopId
     * @param bool   $updateViewData If needs to update view data when shop Id changes.
     *
     * @return string
     */
    protected function updateShopIdByUser($user, $shopId, $updateViewData = false)
    {
        $shopId = parent::updateShopIdByUser($user, $shopId, $updateViewData);

        // check if we right now saved a new entry
        if ($shopId && ('malladmin' != $user->oxuser__oxrights->value) && ($shopId != $user->oxuser__oxrights->value)) {
            $shopId = $user->oxuser__oxrights->value;
            if ($updateViewData) {
                $this->_aViewData['oxid'] = $shopId;
            }
        }

        return $shopId;
    }

    /**
     * @inheritdoc
     */
    protected function checkParent($shop)
    {
        parent::checkParent($shop);

        //loading parent
        if ($shop->oxshops__oxparentid->value) {
            // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
            $masterDb = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();

            $selectShopParentQuery = "select oxname from oxshops where oxid = :oxid";
            $shopParentName = $masterDb->getOne($selectShopParentQuery, [
                ':oxid' => $shop->oxshops__oxparentid->value
            ]);
            $this->_aViewData["parentName"] = $shopParentName;
        }
    }

    /**
     * No need to unset Shop ID as it is used in enterprise edition.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function updateParameters($parameters)
    {
        return $parameters;
    }

    /**
     * @inheritdoc
     */
    protected function checkExceptionType($exception)
    {
        parent::checkExceptionType($exception);

        if ($exception->getMessage() == 'SHOP_MAIN_MAXSHOP_WARNING') {
            $this->_aViewData['sMaxShopWarning'] = true;
        }
    }

    /**
     * @inheritdoc
     */
    protected function canCreateShop($shopId, $shop)
    {
        $canCreateShop = parent::canCreateShop($shopId, $shop);
        if ($canCreateShop && $shopId == self::NEW_SHOP_ID) {
            $config = $this->getConfig();

            //copying oxbaseshop parameters
            $baseShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
            $baseShop->setEnableMultilang(false);
            $shop->setEnableMultilang(false);
            $baseShop->load("1");

            //reseting exceptions
            $baseShop->oxshops__oxismultishop = 0;
            $baseShop->oxshops__oxissupershop = 0;
            $baseShop->oxshops__oxisinherited = 0;

            $properties = get_object_vars($baseShop);
            foreach ($properties as $key => $property) {
                if ($baseShop->$key->value && !$shop->$key->value) {
                    $shop->$key = $property;
                }
            }

            $shop->oxshops__oxactive->setValue(0);
            $shop->oxshops__oxproductive->setValue(0);

            //checking mandate count
            if ($config->getMandateCount() >= $config->getConfigParam('iMaxMandates')) {
                $this->_aViewData['sMandateWarning'] = true;

                $canCreateShop = false;
            }
        }

        return $canCreateShop;
    }

    /**
     * @inheritdoc
     */
    protected function updateShopInformation($config, $shop, $shopId)
    {
        parent::updateShopInformation($config, $shop, $shopId);
        // set oxid if inserted and copy main parameters from oxbaseshop
        if ($shopId == self::NEW_SHOP_ID) {
            $this->copyProjectShopConfiguration($shop->getId());

            // copy static seo urls
            \OxidEsales\Eshop\Core\Registry::getSeoEncoder()->copyStaticUrls($shop->getId());
            $this->setEditObjectId($shop->getId());

            //copy contents
            $shopContentList = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
            $shopContentList->init("oxi18n", 'oxcontents');
            $shopContentList->getBaseObject()->setEnableMultilang(false);

            $shopContentList->selectString("select * from oxcontents where oxshopid = '1'");
            foreach ($shopContentList as $shopContent) {
                $shopContent->oxcontents__oxshopid->setValue($shop->getId());
                $shopContent->setId();
                $shopContent->save();
            }

            $this->_copyConfigVars($shop);

            $multiShopTables = $config->getConfigParam('aMultiShopTables');
            $shop->setMultiShopTables($multiShopTables);

            $shop->updateInheritance();

            //regenerating shop views
            $mallInherit = array();
            foreach ($multiShopTables as $table) {
                $mallInherit[$table] = $config->getShopConfVar('blMallInherit_' . $table, $shop->getId());
            }
            $shop->generateViews(false, $mallInherit);

            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable("actshop", $shop->getId());
            $config->setShopId($shop->getId());

            // reloading navigation frame
            $this->_aViewData["updatenav"] = "1";

            //skipping requirements checking when reloading nav frame
            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable("navReload", true);
        }
    }

    /**
     * @param int $newShopId
     */
    private function copyProjectShopConfiguration(int $newShopId)
    {
        $container = $this->getContainer();
        /** @var ShopConfigurationGeneratorBridgeInterface $environmentConfigurationBridge */
        $environmentConfigurationBridge = $container->get(ShopConfigurationGeneratorBridgeInterface::class);
        $environmentConfigurationBridge->generateForShop($newShopId);
    }
}
