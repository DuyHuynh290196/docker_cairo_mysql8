<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

use oxDb;
use OxidEsales\Eshop\Core\Edition\EditionPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionRootPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionSelector;
use OxidEsales\Eshop\Core\FileSystem\FileSystem;

/**
 * Main shop configuration class.
 */
class Config extends \OxidEsales\EshopCommunity\Core\Config
{
    /**
     * Checks if the shop has demo shop license key
     *
     * @return bool
     */
    public function hasDemoKey()
    {
        $oSerial = $this->getSerial();

        if ($oSerial->isFlagEnabled("demoshop")) {
            return true;
        }

        return false;
    }

    /**
     * Returns OXID eShop edition.
     *
     * @deprecated since v6.0.0-rc.2 (2017-08-24); Use \OxidEsales\Facts\Facts::getEdition() instead.
     *
     * @return string
     */
    public function getEdition()
    {
        return "PE";
    }

    /**
     * Returns full eShop edition name
     *
     * @return string
     */
    public function getFullEdition()
    {
        $sEdition = $this->getEdition();
        if ($sEdition == "PE") {
            $sEdition = "Professional Edition";
        }

        return $sEdition;
    }

    /**
     * Checks version of shop, returns:
     *  0 - version is bellow 2.2
     *  1 - Demo or unlicensed
     *  2 - Pro
     *  3 - Enterprise
     *
     * @return int
     */
    public function detectVersion()
    {
        $oSerial = $this->getSerial();

        return $oSerial->detectVersion($oSerial->sSerial);
    }

    /**
     * Returns oxSerial object for serial key validation
     *
     * @param bool $blReload set true to reload cached serial object
     *
     * @return Serial
     */
    public function getSerial($blReload = false)
    {
        $sShopID = $this->getShopId();
        if (!isset($this->_oSerial) || $blReload) {
            $this->_oSerial = oxNew(\OxidEsales\Eshop\Core\Serial::class);
            $this->_oSerial->sSerial = $this->getSession()->getVariable($sShopID . 'oxserial');
        }

        if (!$this->_oSerial->sSerial) {
            $this->_oSerial->sSerial = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select oxserial from oxshops where oxid = :oxid', [':oxid' => $sShopID]);
            $this->getSession()->setVariable($sShopID . 'oxserial', $this->_oSerial->sSerial);
        }

        return $this->_oSerial;
    }

    /**
     * @inheritdoc
     */
    protected function getEditionTemplate($templateName)
    {
        $templatesPath = $this->getPathSelector()->getViewsDirectory();

        $fileSystem = oxNew(FileSystem::class);
        $templatePath = $fileSystem->combinePaths($templatesPath, $templateName);
        if (!$fileSystem->isReadable($templatePath)) {
            $templatePath = parent::getEditionTemplate($templateName);
        }

        return $templatePath;
    }

    /**
     * @return EditionPathProvider
     */
    private function getPathSelector()
    {
        return new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
    }
}
