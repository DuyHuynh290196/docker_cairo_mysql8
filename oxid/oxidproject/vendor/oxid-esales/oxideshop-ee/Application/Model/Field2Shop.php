<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxArticle;
use oxDb;
use oxField;

/**
 * Order delivery manager.
 * Currently calculates price/costs.
 */
class Field2Shop extends \OxidEsales\Eshop\Core\Model\MultiLanguageModel
{
    /**
     * Current class name
     *
     * @var string
     */
    protected $_sClassName = 'oxfield2shop';

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('oxfield2shop');
    }

    /**
     * Returns multishop fields array
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getMultiShopFields" in next major
     */
    protected function _getMultiShopFields() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // cache getter !!!!


        $aMultishopArticleFields = (array) $this->getConfig()->getConfigParam('aMultishopArticleFields');
        if (!$this->_blEmployMultilanguage) {
            $aMlFields = array();
            foreach ($aMultishopArticleFields as $sField) {
                if ($this->_getFieldStatus($sField)) {
                    $aMlFields[] = $sField;
                }
            }

            if (count($aMlFields)) {
                $sMatch = '/(' . implode('|', $aMlFields) . ')_[0-9]+/i';
                foreach ($this->fetchTableFields() as $oField) {
                    if (preg_match($sMatch, $oField->name)) {
                        $aMultishopArticleFields[] = $oField->name;
                    }
                }
            }
        }

        return $aMultishopArticleFields;
    }

    /**
     * Copies and saves shop data from product
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $oProduct product to copy
     *
     * @return null
     */
    public function saveProductData($oProduct)
    {
        foreach ($this->_getMultiShopFields() as $sField) {
            $sField = strtolower($sField);
            if ($sField === "oxlongdesc") {
                $this->{"oxfield2shop__{$sField}"} = clone $oProduct->getLongDescription();
            } elseif (isset($oProduct->{"oxarticles__{$sField}"})) {
                $this->{"oxfield2shop__{$sField}"} = clone $oProduct->{"oxarticles__{$sField}"};
            }
        }

        $myConfig = $this->getConfig();

        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        $masterDb = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();
        $this->oxfield2shop__oxartid = new \OxidEsales\Eshop\Core\Field($oProduct->getId());
        $this->oxfield2shop__oxshopid = new \OxidEsales\Eshop\Core\Field($myConfig->getShopId());

        $sQ = "select oxid from oxfield2shop where oxartid = :oxartid and oxshopid = :oxshopid";
        $this->setId($masterDb->getOne($sQ, [
            ':oxartid' => $oProduct->getId(),
            ':oxshopid' => $myConfig->getShopId()
        ]));

        return (bool) $this->save();
    }

    /**
     * Assigns shop data to product
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $oProduct product to assign
     */
    public function setProductData($oProduct)
    {
        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        $masterDb = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();
        $sQ = "select oxid from oxfield2shop where oxartid = :oxartid and oxshopid = :oxshopid";
        $sId = $masterDb->getOne($sQ, [
            ':oxartid' => $oProduct->getId(),
            ':oxshopid' => \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId()
        ]);

        if ($this->load($sId)) {
            foreach ($this->_getMultiShopFields() as $sField) {
                $sField = strtolower($sField);
                $sThisField = "oxfield2shop__{$sField}";
                $sThisProd = "oxarticles__{$sField}";
                if (isset($this->$sThisField) && ($this->$sThisField->value || $this->$sThisField->value === '0')) {
                    if ($sField === "oxlongdesc") {
                        $oProduct->setArticleLongDesc($this->$sThisField->getRawValue());
                    } else {
                        $oProduct->$sThisProd = clone $this->$sThisField;
                    }
                }
            }
        }
    }

    /**
     * Removes orphan oxfield2shop records. For article $sArticleID if supplied or for all articles.
     *
     * @param string $sShopId    Shop id
     * @param string $sArticleID Article id
     */
    public function cleanMultishopFields($sShopId, $sArticleID = null)
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $params = [];

        //$sIdSelect = $myConfig->getBaseShopId();; makes no sense, removed therefore MAFI
        if ($sArticleID) {
            $sIdSelect = "f2s.oxartid = :oxartid";
            $params[':oxartid'] = $sArticleID;
        }

        //somehow MySQL looks like does not understands "delete from oxfield2shop AS A"
        //so we split it into 2 select/delete queries
        $sArticleTable = getViewName('oxarticles', null, $sShopId);
        $sTmpSelect = "select f2s.oxid from oxfield2shop as f2s ";
        $sTmpSelect .= "left join {$sArticleTable} as oxv ";
        $sTmpSelect .= "on oxv.oxid = f2s.oxartid ";
        $sTmpSelect .= "where oxv.oxid is null and ";
        $sTmpSelect .= "f2s.oxshopid = :oxshopid ";
        $params[':oxshopid'] = $sShopId;

        if (strlen($sIdSelect) > 1) {
            $sTmpSelect .= "and " . $sIdSelect;
        }
        $rsDel = $oDb->select($sTmpSelect, $params);
        $aDeletable = array();
        while ($rsDel && $rsDel->count() > 0 && !$rsDel->EOF) {
            $aDeletable[] = $rsDel->fields[0];
            $rsDel->fetchRow();
        }

        if (count($aDeletable)) {
            //do finally deleting
            $sDeleteable = join("', '", $aDeletable);
            $sDelete = "delete from oxfield2shop where oxid IN ('$sDeleteable')";

            $oDb->execute($sDelete);
        }
    }

    /**
     * Fetch the fields, which belong to the table of this object.
     *
     * @return array The fields of the table, corresponding to this object.
     */
    protected function fetchTableFields()
    {
        return \OxidEsales\Eshop\Core\DatabaseProvider::getInstance()->getTableDescription($this->_sCoreTable);
    }
}
