<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Application\Controller\Admin;

use oxAdminDetails;

/**
 * @inheritdoc
 */
class ShopLicense extends \OxidEsales\EshopCommunity\Application\Controller\Admin\ShopLicense
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        \OxidEsales\Eshop\Core\Registry::getSession()->setVariable("actshop", $this->getEditObjectId());
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function render()
    {
        $templateName = parent::render();

        // Gather serials
        $config = $this->getConfig();
        $serialsList = $config->getConfigParam('aSerials');

        $this->_aViewData["oxserials"] = $serialsList;

        $serial = $config->getSerial(true);
        $serial->disableCacheModules();

        //renew module template data
        $serialList = array();

        $this->_aViewData['aSerial'] = $serialList;

        return $templateName;
    }

    /**
     * Saves shop Licensing data.
     *
     * @return null
     */
    public function save()
    {
        $config = $this->getConfig();

        if ($this->_canUpdate()) {
            oxAdminDetails::save();

            $language = \OxidEsales\Eshop\Core\Registry::getLang();
            $parameters = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("editval");
            //saving serial number
            $serialToAdd = trim($parameters['oxnewserial']);

            $serial = oxNew(\OxidEsales\Eshop\Core\Serial::class);
            $serial->disableCacheModules();

            //if serial already exists we skip it
            $serialsList = $config->getConfigParam('aSerials');
            if (is_array($serialsList) && in_array($serialToAdd, $serialsList)) {
                return;
            }

            //perform online license key check
            $olc = $this->getOnlineLicenseCheck();
            if (!$olc->validateNewSerial($serialToAdd)) {
                $this->_aViewData['error'] = $language->translateString($olc->getErrorMessage());

                return;
            }

            if ($serial->isValidSerial($serialToAdd) && (!is_array($serialsList) || !in_array($serialToAdd, $serialsList))) {
                //processing error messages
                if (!$serial->isStackable($serialToAdd) && !empty($serialsList)) {
                    $this->_aViewData['error'] = $language->translateString('nonstackable_serial_detected');
                }

                //so we have four case of behabiour with adding new serial
                //1. New serial is stackable so we just add it, but no demo stackables are allowed on non demo serials and vice versa
                //2. New serial is non demo serial and existing serial is demo then we delete old and leave new one (in spite if new is stackable or not)
                //3. New serial is not stackable and there are no existing serial so we just add the new one.
                //4. New serial is not stackable and existing serial is not demo (or any other case) so we just not add it
                //      as it may be Eric's mistake or wrong serial

                //echo "existing serial: $myConfig->sSerialNr";
                $serialNr = $config->getConfigParam('sSerialNr');
                if ($serial->isStackable($serialToAdd) && !($serial->detectVersion($serialNr) == 1 xor $serial->detectVersion($serialToAdd) == 1)) {
                    //1.
                    $serialsList[] = $serialToAdd;
                    $this->_aViewData['message'] = $language->translateString('serial_added');
                    $this->_aViewData['error'] = '';
                }

                if ($serial->detectVersion($serialNr) == 1 && $serial->detectVersion($serialToAdd) != 1) {
                    //2.
                    $serialsList = array($serialToAdd);
                    $this->_aViewData['message'] = $language->translateString('serial_updated');
                    $this->_aViewData['error'] = '';
                }

                if (!$serial->isStackable($serialToAdd) && empty($serialsList)) {
                    //3.
                    $serialsList = array($serialToAdd);
                }

                //4. Do nothing
                $config->setConfigParam('aSerials', $serialsList);

                $this->updateShopSerial();
            } else {
                $this->_aViewData['error'] = $language->translateString('invalid_serial');
            }

            $config->saveShopConfVar("arr", "aSerials", $config->getConfigParam('aSerials'), $config->getBaseShopId());
        }
    }

    /**
     * OLC dependency getter
     *
     * @return \OxidEsales\Eshop\Core\OnlineLicenseCheck
     */
    public function getOnlineLicenseCheck()
    {
        $curl = oxNew(\OxidEsales\Eshop\Core\Curl::class);
        $emailBuilder = oxNew(\OxidEsales\Eshop\Core\OnlineServerEmailBuilder::class);
        $simpleXml = oxNew(\OxidEsales\Eshop\Core\SimpleXml::class);
        $licenseCaller = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheckCaller::class, $curl, $emailBuilder, $simpleXml);
        $userCounter = oxNew(\OxidEsales\Eshop\Core\UserCounter::class);

        /** @var \OxidEsales\Eshop\Core\Service\ApplicationServerExporter $appServerExporter */
        $appServerExporter = $this->getApplicationServerExporter();

        $onlineLicenseCheck = oxNew(\OxidEsales\Eshop\Core\OnlineLicenseCheck::class, $licenseCaller);
        $onlineLicenseCheck->setAppServerExporter($appServerExporter);
        $onlineLicenseCheck->setUserCounter($userCounter);

        return $onlineLicenseCheck;
    }

    /**
     * Delete serial
     */
    public function deleteSerial()
    {
        $config = $this->getConfig();
        if ($this->_canUpdate()) {
            $newSerialsList = array();
            $serialsList = $config->getConfigParam('aSerials');
            $delSerial = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("serial");
            foreach ($serialsList as $serial) {
                if ($serial != $delSerial) {
                    $newSerialsList[] = $serial;
                }
            }

            $config->saveShopConfVar("arr", "aSerials", $newSerialsList, $config->getBaseShopId());
            $this->updateShopSerial();
        }
    }

    /**
     * Updates serial
     *
     * @return null
     */
    public function updateShopSerial()
    {
        if (!$this->_canUpdate()) {
            return;
        }

        $config = $this->getConfig();

        //it summs all aSerials from config and then inserts the sum to oxshops
        //and caluclates maxdays maxarticles and maxshops
        $serialsList = $config->getConfigParam('aSerials');

        $shopSerial = '';
        $maxDays = 0;
        $maxArticles = 0;
        $maxShops = 0;

        if (is_array($serialsList) && count($serialsList)) {
            $serial = oxNew(\OxidEsales\Eshop\Core\Serial::class);
            $serial->disableCacheModules();
            $shopSerial = $serialsList[0];
            $maxDays = $serial->getMaxDays($serialsList[0]);
            $maxArticles = $serial->getMaxArticles($serialsList[0]);
            $maxShops = $serial->getMaxShops($serialsList[0]);

            for ($i = 1; $i < count($serialsList); $i++) {
                $maxDays += $serial->getMaxDays($serialsList[$i]);
                $maxArticles += $serial->getMaxArticles($serialsList[$i]);
                $maxShops += $serial->getMaxShops($serialsList[$i]);
                $shopSerial = $serial->addSerial($shopSerial, $serialsList[$i]);
            }
        }

        //saving config max values
        $baseShopId = $config->getBaseShopId();
        $config->saveShopConfVar('str', 'IMD', $maxDays, $baseShopId);
        $config->saveShopConfVar('str', 'IMA', $maxArticles, $baseShopId);
        $config->saveShopConfVar('str', 'IMS', $maxShops, $baseShopId);

        //setting the shop serial
        $oldSerial = $config->getConfigParam('sSerialNr');
        if ($oldSerial != $shopSerial) {
            //storing
            $config->getSerial()->sSerial = $shopSerial;
            $config->setConfigParam('sSerialNr', $shopSerial);

            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable($baseShopId . 'oxserial', $shopSerial);

            //saving for all shops
            $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $db->execute("update oxshops set oxserial = :oxserial", [
                ':oxserial' => $shopSerial
            ]);
            //setting to unexpired shop again

            $config->saveShopConfVar('bool', 'sShopVar', '', $baseShopId);
        }
        $this->_aViewData['isdemoversion'] = $config->detectVersion() == 1;
    }

    /**
     * ApplicationServerExporter dependency setter
     *
     * @return \OxidEsales\Eshop\Core\Service\ApplicationServerExporterInterface
     */
    protected function getApplicationServerExporter()
    {
        $config = \OxidEsales\Eshop\Core\Registry::getConfig();
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $appServerDao = oxNew(\OxidEsales\Eshop\Core\Dao\ApplicationServerDao::class, $database, $config);
        $utilsServer = oxNew(\OxidEsales\Eshop\Core\UtilsServer::class);

        $appServerService = oxNew(
            \OxidEsales\Eshop\Core\Service\ApplicationServerService::class,
            $appServerDao,
            $utilsServer,
            \OxidEsales\Eshop\Core\Registry::get("oxUtilsDate")->getTime()
        );
        return oxNew(\OxidEsales\Eshop\Core\Service\ApplicationServerExporter::class, $appServerService);
    }
}
