<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use oxDb;

/**
 * @inheritdoc
 */
class ViewConfig extends \OxidEsales\EshopProfessional\Core\ViewConfig
{
    /**
     * @inheritdoc
     */
    protected function isStartClassRequired()
    {
        $shopConfig = $this->getConfig();
        // If more than one shop is active and shop selection must be shown
        if (
            (bool) $shopConfig->getConfigParam('iMallMode') &&
            (bool) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("select count(*) > 1 from oxshops where oxactive = 1")
        ) {
            $isStartRequired = true;
        } else {
            $isStartRequired = parent::isStartClassRequired();
        }

        return $isStartRequired;
    }

    /**
     * @inheritdoc
     */
    protected function getAdditionalRequestParameters()
    {
        $additionalFields = '';
        $shopConfig = $this->getConfig();
        if ($shopConfig->mustAddShopIdToRequest()) {
            $additionalFields .= "\n<input type=\"hidden\" name=\"shp\" value=\"" . $shopConfig->getShopId() . "\" />";
        }

        return $additionalFields;
    }

    /**
     * Returns shops serial key.
     *
     * @return string
     */
    public function getSerial()
    {
        if (($sValue = $this->getViewConfigParam('license')) === null) {
            $sValue = $this->getConfig()->getSerial()->sSerial;
            $this->setViewConfigParam('license', $sValue);
        }

        return $sValue;
    }

    /**
     * Checks if the shop is in staging mode.
     *
     * @return bool
     */
    public function isStagingMode()
    {
        return $this->getConfig()->isStagingMode();
    }
}
