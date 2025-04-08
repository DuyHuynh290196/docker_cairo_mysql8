<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\EshopEnterprise\Core\Element2ShopRelationsSqlGenerator;
use OxidEsales\EshopEnterprise\Core\Element2ShopRelationsSqlGeneratorUpdatable;

/**
 * @inheritdoc
 */
class Object2Category extends \OxidEsales\EshopProfessional\Application\Model\Object2Category
{
    /** @var Element2ShopRelationsSqlGenerator */
    private $originalElement2ShopSqlGenerator;

    /**
     * Adds object to related map for inherited subshops
     * @deprecated underscore prefix violates PSR12, will be renamed to "addElement2ShopRelations" in next major
     */
    protected function _addElement2ShopRelations() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aShopIds = $this->_getInheritanceGroup();
        if (count($aShopIds) > 1) {
            array_shift($aShopIds);
            $oElement2ShopRelations = $this->_getElement2ShopRelations();
            $oElement2ShopRelations->setShopIds($aShopIds);
            $oElement2ShopRelations->addObjectToShop($this);
        }
    }

    /**
     * Returns a list of subshop ids, including the parent.
     * If it is a supershop it checks for all subshops that assigned article and category is in.
     * If not it checks for inherited subshops and if the config option for the bulk inheritance is on.
     * The items are considered inherited in case it is inherited directly to the subshop, recursive check is performed
     * for subsequent subshops.
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getInheritanceGroup" in next major
     */
    protected function _getInheritanceGroup() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oShop = $this->getConfig()->getActiveShop();
        if ($oShop->isSuperShop()) {
            $aShopIds = $this->_getInheritanceGroupForSuperShop();
        } else {
            $sCoreTable = $this->getCoreTableName();
            $aShopIds = $oShop->getInheritanceGroup($sCoreTable);
        }

        return $aShopIds;
    }

    /**
     * Returns a id's list of subshop, in which assigned product and category are.
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getInheritanceGroupForSuperShop" in next major
     */
    protected function _getInheritanceGroupForSuperShop() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aProductShopIds = $this->_getProductShopIds();
        $aCategoryShopIds = $this->_getCategoryShopIds();

        $aShopIds = array_intersect($aProductShopIds, $aCategoryShopIds);

        return $aShopIds;
    }

    /**
     * Returns shop id's of assigned product
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getProductShopIds" in next major
     */
    protected function _getProductShopIds() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->load($this->getProductId());
        $aProductShopIds = $oArticle->getItemAssignedShopIds();

        return $aProductShopIds;
    }

    /**
     * Returns shop id's of assigned category
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getCategoryShopIds" in next major
     */
    protected function _getCategoryShopIds() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oCategory = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $oCategory->load($this->getCategoryId());
        $aCategoryShopIds = $oCategory->getItemAssignedShopIds();

        return $aCategoryShopIds;
    }

    /** @inheritDoc */
    protected function _update(): bool // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_update();
        $this->storeOriginalSqlGenerator();
        $this->makeElement2ShopRelationsUpdatable();
        $this->_addElement2ShopRelations();
        $this->restoreOriginalSqlGenerator();
        return true;
    }

    private function storeOriginalSqlGenerator(): void
    {
        $this->originalElement2ShopSqlGenerator = $this->_getElement2ShopRelations()
            ->getDbGateway()
            ->getSqlGenerator();
    }

    private function makeElement2ShopRelationsUpdatable(): void
    {
        $this->_getElement2ShopRelations()
            ->getDbGateway()
            ->setSqlGenerator(
                new Element2ShopRelationsSqlGeneratorUpdatable()
            );
    }

    private function restoreOriginalSqlGenerator(): void
    {
        $this->_getElement2ShopRelations()
            ->getDbGateway()
            ->setSqlGenerator($this->originalElement2ShopSqlGenerator);
    }
}
