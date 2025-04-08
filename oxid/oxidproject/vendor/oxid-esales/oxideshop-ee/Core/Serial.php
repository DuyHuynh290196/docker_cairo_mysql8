<?php

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class Serial extends \OxidEsales\EshopProfessional\Core\Serial
{
    /**
     * Returns true if master-slave licence is valid.
     *
     * @return bool
     */
    public function isMasterSlaveLicenseValid()
    {
        return $this->isFlagEnabled('master_slave');
    }

    /**
     * @inheritdoc
     */
    protected function isInvalidBetaSerial($sSerial)
    {
        return false;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "isCorrectMandateAmount" in next major
     */
    protected function _isCorrectMandateAmount() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $blCorrectAmount = true;

        $oConfig = $this->getConfig();

        $iMandateCount = $oConfig->getMandateCount();
        $iMaxMandates = $oConfig->getConfigParam('IMS');
        if ($iMandateCount > $iMaxMandates) {
            $blCorrectAmount = false;
            $this->_sValidationCode = 'incorrect_mandate_amount';
        }

        return $blCorrectAmount;
    }
}
