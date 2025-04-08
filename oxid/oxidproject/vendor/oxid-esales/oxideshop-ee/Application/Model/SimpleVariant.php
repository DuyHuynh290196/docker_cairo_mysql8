<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxPrice;

/**
 * @inheritdoc
 */
class SimpleVariant extends \OxidEsales\EshopProfessional\Application\Model\SimpleVariant
{
    /**
     * Assigns to oxarticle object some base parameters/values (such as
     * detaillink, moredetaillink, etc).
     *
     * @param array $currentRecord Array representing current field values
     */
    public function assign($currentRecord)
    {
        // load object from database
        parent::assign($currentRecord);

        // assign only for a first load time
        if (!$this->isLoaded()) {
            // load data for subshops
            $this->_setShopValues($this);
        }
    }

    /**
     * Modifies price according to special subshop addition
     *
     * @param float $price
     */
    public function modifyGroupPrice($price)
    {
        $price = parent::modifyGroupPrice($price);

        $myConfig = $this->getConfig();
        // mall add price stuff
        // MALL ON
        if ($myConfig->isMall() && !$this->isAdmin()) {
            //adding shop addition
            if ($myConfig->getConfigParam('iMallPriceAddition')) {
                if ($myConfig->getConfigParam('blMallPriceAdditionPercent')) {
                    $price += \OxidEsales\Eshop\Core\Price::percent($price, $myConfig->getConfigParam('iMallPriceAddition'));
                } else {
                    $price += $myConfig->getConfigParam('iMallPriceAddition');
                }
            }
        }

        return $price;
    }

    /**
     * Sets shop specific article information from oxfield2shop table
     * (default are oxprice, oxpricea, oxpriceb, oxpricec
     * (specified in \OxidEsales\Eshop\Core\Config::aMultishopArticleFields param))
     *
     * @param object $oArticle Article object
     * @deprecated underscore prefix violates PSR12, will be renamed to "setShopValues" in next major
     */
    protected function _setShopValues($oArticle) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $myConfig = $this->getConfig();
        $sShopId = $myConfig->getShopID();
        $aMultishopArticleFields = $myConfig->getConfigParam('aMultishopArticleFields');
        if ($myConfig->getConfigParam('blMallCustomPrice') && $sShopId != $oArticle->oxarticles__oxshopid->value && is_array($aMultishopArticleFields)) {
            $oField2Shop = oxNew(\OxidEsales\Eshop\Application\Model\Field2Shop::class);
            $oField2Shop->setEnableMultilang($this->_blEmployMultilanguage);
            $oField2Shop->setLanguage($this->getLanguage());
            $oField2Shop->setProductData($this);
        }
    }
}
