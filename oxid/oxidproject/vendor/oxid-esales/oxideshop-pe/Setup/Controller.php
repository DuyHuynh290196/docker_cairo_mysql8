<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Setup;

use Exception;

/** Setup controller class. */
class Controller extends \OxidEsales\EshopCommunity\Setup\Controller
{
    /**
     * Renders serial input fields
     */
    public function serial()
    {
        $this->setViewOptions(
            'serial.php',
            'STEP_5_TITLE',
            [
                "sLicense" => $this->getSetupInstance()->getDefaultSerial(),
            ]
        );
    }

    /**
     * Saves serial key
     *
     * Functionality is tested via:
     *   `Acceptance/Frontend/ShopSetUpTest.php::testSetupShowsErrorMessageWhenAnInvalidLicenseIsEnteredAndRedirectsToPreviousTab`
     */
    public function serialSave()
    {
        $view = $this->getView();

        try {
            $licence = (string) $this->getUtilitiesInstance()->getRequestVar('sLicence', 'post');
            $view->setMessage($this->getSetupInstance()->setSerial(trim($licence)));
        } catch (Exception $exception) {
            $view->setMessage($exception->getMessage());
        }

        $this->setViewOptions('default.php', 'STEP_5_1_TITLE', []);
    }

    /**
     * @inheritdoc
     */
    protected function onDirsWriteSetStep($setup)
    {
        $setup->setNextStep($setup->getStep('STEP_SERIAL'));
    }
}
