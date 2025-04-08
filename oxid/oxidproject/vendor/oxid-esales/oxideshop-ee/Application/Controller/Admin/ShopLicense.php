<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ShopLicense extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ShopLicense
{
    /**
     * @inheritdoc
     */
    public function render()
    {
        $templateName = parent::render();

        //TV2007-02-27
        $allowedMandateCount = 0;
        $config = $this->getConfig();
        $serialsList = $config->getConfigParam('aSerials');

        $serial = $config->getSerial(true);
        $serial->disableCacheModules();

        if (is_array($serialsList)) {
            foreach ($serialsList as $serialKey) {
                $allowedMandateCount += $serial->getMaxShops($serialKey);
            }
        }
        if ($allowedMandateCount > 100000000) {
            $allowedMandateCount = 'unlimited';
        }
        $this->_aViewData['iAllowedMandateCount'] = $allowedMandateCount;
        $this->_aViewData['iUsedMandateCount'] = $config->getMandateCount();

        return $templateName;
    }
}
