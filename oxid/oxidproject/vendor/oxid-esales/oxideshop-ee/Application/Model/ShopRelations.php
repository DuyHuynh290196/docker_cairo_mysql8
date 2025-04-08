<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * This class handles relations between shops.
 *
 * For example: article (or other elements) are inherited by shop from parent shop.
 *
 * @internal Do not make a module extension for this class.
 */
class ShopRelations
{
    /** @var string Shop Id. */
    private $_sShopId = null;

    /** @var string Element type (table name to add to shop). */
    private $_sElementType = null;

    /** @var bool Multi Shop flag. */
    private $_blIsMultiShopType = false;

    /**
     * Constructor class.
     *
     * @param string $shopId Shop id
     */
    public function __construct($shopId)
    {
        $this->setShopId($shopId);
    }

    /**
     * Sets shop ID.
     *
     * @param string $shopId Shop ID.
     */
    public function setShopId($shopId)
    {
        $this->_sShopId = $shopId;
    }

    /**
     * Gets shop ID.
     *
     * @return string
     */
    public function getShopId()
    {
        return $this->_sShopId;
    }

    /**
     * Sets multi shop flag.
     *
     * @param bool $isMultiShopType Is multi shop flag.
     */
    public function setIsMultiShopType($isMultiShopType)
    {
        $this->_blIsMultiShopType = $isMultiShopType;
    }

    /**
     * Gets multi shop flag.
     *
     * @return bool
     */
    public function isMultiShopType()
    {
        return $this->_blIsMultiShopType;
    }

    /**
     * Sets Element type - table name of element.
     *
     * @param string $elementType Type (table name) of Element to add to shop
     */
    public function setElementType($elementType)
    {
        $this->_sElementType = $elementType;
    }

    /**
     * Gets Element type - table name of element.
     *
     * @return string
     */
    public function getElementType()
    {
        return $this->_sElementType;
    }

    /**
     * Checks if given element is inherited from parent shop.
     *
     * @param string $elementName The name of element to check.
     *
     * @return bool
     */
    public function isShopElementInherited($elementName)
    {
        if ($this->isMultiShopType()) {
            $isInherited = $this->_isMultiShopElementInherited($elementName);
        } else {
            $isInherited = $this->_isSubShopElementInherited($elementName);
        }

        return $isInherited;
    }

    /**
     * Checks if given element is inherited from parent shop.
     *
     * @param string $elementName The name of element to check.
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "isSubShopElementInherited" in next major
     */
    protected function _isSubShopElementInherited($elementName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $isInherited = false;
        if ($elementName == 'oxcategories') {
            return $isInherited;
        }

        $shopId = $this->getShopId();

        $variableValue = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('blMallInherit_' . strtolower($elementName), $shopId);
        if (isset($variableValue)) {
            $isInherited = $variableValue;
        }

        return $isInherited;
    }

    /**
     * Checks if given element is inherited from all shop.
     * For Multi shops all elements are inherited by default except of categories.
     * If element name is oxcategories it checks for the option.
     *
     * @param string $elementName The name of element to check.
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "isMultiShopElementInherited" in next major
     */
    protected function _isMultiShopElementInherited($elementName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $isInherited = true;

        if ($elementName == 'oxcategories') {
            $isInherited = false;
            $shopId = $this->getShopId();
            $variableValue = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar('blMultishopInherit_oxcategories', $shopId);
            if (isset($variableValue)) {
                $isInherited = $variableValue;
            }
        }

        return $isInherited;
    }
}
