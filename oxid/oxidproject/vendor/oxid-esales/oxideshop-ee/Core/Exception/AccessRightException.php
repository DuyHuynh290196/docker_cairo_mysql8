<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Exception;

/**
 * Exception class thrown when a violation of access rights is commited, e.g.:
 * - no rights to view area
 */
class AccessRightException extends \OxidEsales\Eshop\Core\Exception\StandardException
{
    /** @var string To what object the access was denied */
    protected $_sObjectName = null;

    /**
     * Class name of the object which caused the RR exception
     *
     * @param string $sObjectName Class name of the object
     */
    public function setObjectName($sObjectName)
    {
        $this->_sObjectName = $sObjectName;
    }

    /**
     * Class name of the object that caused the RR exception
     *
     * @return string
     */
    public function getObjectName()
    {
        return $this->_sObjectName;
    }

    /**
     * Get string dump
     * Overrides oxException::getString()
     *
     * @return string
     */
    public function getString()
    {
        return __CLASS__ . '-' . parent::getString() . " Faulty Object --> " . $this->_sObjectName . "\n";
    }

    /**
     * Override of Exception::getValues()
     *
     * @return array
     */
    public function getValues()
    {
        $aRes = parent::getValues();
        $aRes['object'] = $this->getObjectName();

        return $aRes;
    }
}
