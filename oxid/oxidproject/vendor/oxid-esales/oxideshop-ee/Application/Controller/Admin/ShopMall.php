<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use oxDb;

/**
 * Admin shop system setting manager.
 * Collects shop system settings, updates it on user submit, etc.
 * Admin Menu: Main Menu -> Core Settings -> System.
 */
class ShopMall extends \OxidEsales\Eshop\Application\Controller\Admin\ShopConfiguration
{
    /**
     * Executes parent method parent::render() and returns name of template
     * file "shop_mall.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $config = $this->getConfig();
        $currencies = $config->getCurrencyArray();

        $this->_aViewData['defCur'] = $currencies[0]->name;

        //preventing edit for anyone except malladmin
        if (!\OxidEsales\Eshop\Core\Registry::getSession()->getVariable("malladmin")) {
            $this->_aViewData["readonly"] = true;
        }

        //sometimes we may want to prevent showing regenerate views button
        //but up to 2006-10-22 (2.7.0.2) it is not used anywhere
        $showUpdateViews = $config->getConfigParam('blShowUpdateViews');
        $this->_aViewData['showViewUpdate'] = (isset($showUpdateViews) && !$showUpdateViews) ? false : true;
        $this->_aViewData['showInheritanceUpdate'] = false;

        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $selectShopParentQuery = "select oxparentid, oxismultishop from oxshops where oxid = :oxid";
        $shopParent = $db->select($selectShopParentQuery, [
            ':oxid' => $this->getEditObjectId()
        ]);
        if ($shopParent && $shopParent->count() > 0) {
            if ($shopParent->fields[0] && !$shopParent->fields[1]) {
                $this->_aViewData['showInheritanceUpdate'] = true;
            }
        }

        return "shop_mall.tpl";
    }

    /**
     * Saves changed shop configuration parameters.
     *
     * @return bool
     */
    public function save()
    {
        $saveSuccess = false;
        //preventing edit for anyone except malladmin
        if (\OxidEsales\Eshop\Core\Registry::getSession()->getVariable("malladmin")) {
            $saveSuccess = parent::save();
        }

        return $saveSuccess;
    }

    /**
     * Performs full view update
     */
    public function updateViews()
    {
        //preventing edit for anyone except malladmin
        if (\OxidEsales\Eshop\Core\Registry::getSession()->getVariable("malladmin")) {
            $metaData = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
            $this->_aViewData["blViewSuccess"] = $metaData->updateViews();
        }
    }

    /**
     * Changes inheritance information
     */
    public function changeInheritance()
    {
        $config = $this->getConfig();
        $multiShopTables = $config->getConfigParam('aMultiShopTables');
        $editShopId = $config->getRequestParameter("oxid");


        //Saving config vars
        $confBools = $config->getRequestParameter("confbools");
        if (isset($confBools['blMallInherit_oxdelivery'])) {
            //copying delivery parameteres to deliveryset parameters
            $confBools['blMallInherit_oxdeliveryset'] = $confBools['blMallInherit_oxdelivery'];
        }

        //detect only changed values
        $elementWhiteList = $this->_getChangedMultishopTableValues($confBools, $editShopId);

        $_REQUEST['confbools'] = $confBools;
        $_GET['confbools'] = $confBools;
        $_POST['confbools'] = $confBools;

        //save the config values
        $this->save();

        //updating shop inheritance information according to saved config options
        $shop = $this->_getEditShop($editShopId);
        $shop->setMultiShopTables($multiShopTables);
        $shop->updateInheritance($elementWhiteList);

        //regenerating shop views
        $mallInherit = array();
        foreach ($multiShopTables as $tableName) {
            $mallInherit[$tableName] = $config->getShopConfVar('blMallInherit_' . $tableName, $shop->sOXID);
        }
        $shop->generateViews(false, $mallInherit);

        //remove unused oxfield2shop values
        $field2Shop = oxNew(\OxidEsales\Eshop\Application\Model\Field2Shop::class);
        $field2Shop->cleanMultishopFields($shop->sOXID);
    }

    /**
     * Filters only changed values from $aConfBools. And returns changed mulitshop table name array for given shop.
     *
     * @param array $confBools  New Conf var array
     * @param int   $editShopId Shop id
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getChangedMultishopTableValues" in next major
     */
    protected function _getChangedMultishopTableValues($confBools, $editShopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $changed = array();

        $config = $this->getConfig();
        $multiShopTables = $config->getConfigParam('aMultiShopTables');

        foreach ($multiShopTables as $table) {
            $confVarName = $this->_getMultishopInheritConfigVarName($table);
            if (isset($confBools[$confVarName])) {
                $confVar = $confBools[$confVarName] == "true";
                $savedVarValue = $config->getShopConfVar($confVarName, $editShopId);
                if ($confVar != $savedVarValue) {
                    $changed[] = $table;
                }
            }
        }

        return $changed;
    }

    /**
     * Returns "Inherit all" config var name for given table
     *
     * @param string $tableName Table name
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getMultishopInheritConfigVarName" in next major
     */
    protected function _getMultishopInheritConfigVarName($tableName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $confVarName = 'blMallInherit_' . $tableName;

        if ($tableName == "oxcategories") {
            //categories table is an exception
            return "blMultishopInherit_oxcategories";
        }

        return $confVarName;
    }
}
