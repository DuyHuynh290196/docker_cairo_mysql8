<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Setup;

use Exception;
use OxidEsales\Eshop\Core\Edition\EditionSelector;

/**
 * @inheritdoc
 */
class Setup extends \OxidEsales\EshopCommunity\Setup\Setup
{
    protected $setupStepsToAppend = array(
        'STEP_SERIAL'      => 600, // 5
        'STEP_SERIAL_SAVE' => 610, // 51
    );

    /**
     * Returns default serial key
     *
     * @return string
     */
    public function getDefaultSerial()
    {
        return '3Q3EQ-U4562-Y9JTE-2N6LP-JTJ9K-GNVLK';
    }

    /**
     * Shop edition getter, returns 1 if PE, 2 if EE
     *
     * @return int
     */
    public function getEdition()
    {
        $editionSelector = new EditionSelector();
        return $editionSelector->isEnterprise() ? 2 : 1;
    }

    /**
     * Checks if serial is not empty and is valid. In case serial key is ok - writes serial info to db
     *
     * @param string $sSerial serial key
     *
     * @throws Exception exception is thrown if key is not written or is not valid
     *
     * @return string
     */
    public function setSerial($sSerial)
    {
        $language = $this->getInstance("Language");
        $serial = getSerial();
        $serial->setEd($this->getEdition());
        $isValidSerial = $serial->isValidSerial($sSerial);

        // check if important parameters are set
        if (!$sSerial) {
            $this->setNextStep($this->getStep('STEP_SERIAL'));
            throw new Exception($language->getText('ERROR_FILL_ALL_FIELDS'));
        } elseif (!$isValidSerial) {
            $this->setNextStep($this->getStep('STEP_SERIAL'));
            throw new Exception($language->getText('ERROR_BAD_SERIAL_NUMBER'));
        } else {
            $this->getInstance("Database")->writeSerial($serial, $sSerial);
            $this->setNextStep($this->getStep('STEP_FINISH'));

            return $language->getText('STEP_5_1_SERIAL_ADDED');
        }
    }

    /**
     * Merges array and moves finishing step to the end of array.
     *
     * @return array
     */
    public function getSteps()
    {
        $lastStep = $this->_aSetupSteps['STEP_FINISH'];
        $setupSteps = $this->_aSetupSteps;
        unset($setupSteps['STEP_FINISH']);
        $setupSteps = array_merge($setupSteps, $this->setupStepsToAppend);
        $setupSteps['STEP_FINISH'] = $lastStep;
        return $setupSteps;
    }
}
