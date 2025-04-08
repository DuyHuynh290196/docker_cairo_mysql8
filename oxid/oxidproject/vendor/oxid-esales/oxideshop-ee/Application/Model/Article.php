<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxI18n;
use OxidEsales\Eshop\Application\Model\Contract\ArticleInterface;
use OxidEsales\Eshop\Core\Article2ShopRelations;
use OxidEsales\Eshop\Core\Cache\DynamicContent\ContentCache;
use OxidEsales\Eshop\Core\Cache\Generic\Cache;
use OxidEsales\Eshop\Core\Cache\Generic\CacheItem;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Model\MultiLanguageModel;
use OxidEsales\Eshop\Core\Price;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Application\Model\Field2Shop;

/**
 * @inheritdoc
 */
class Article extends \OxidEsales\EshopProfessional\Application\Model\Article implements ArticleInterface
{
    /**
     * 'Stock Changed' dependency event flag.
     *
     * @var int
     */
    const DEPENDENCY_EVENT_STOCK_CHANGED = 1;

    /**
     * 'Article deleted' dependency event flag.
     *
     * @var int
     */
    const DEPENDENCY_EVENT_ARTICLE_DELETED = 2;

    /**
     * Load article even in other non active shops
     *
     * @var boolean
     */
    protected $_blDisableShopCheck = true;

    /**
     * Maximum number of similar products which forces to reset full cache
     */
    protected $_iMaxSimilarForCacheReset = 100;

    /**
     * Checks if current rights allows specified action
     *
     * @param string $articleId ID of the product to be checked
     * @param int    $action    Action to check
     *
     * @return bool
     */
    public function canDo($articleId = null, $action = 1)
    {
        if (!parent::canDo($articleId, $action)) {
            return false;
        }

        $database = DatabaseProvider::getDb();

        $parentArticleId = $this->oxarticles__oxparentid->value;
        if ($articleId) {
            $parentArticleId = $database->getOne("select oxparentid from oxarticles where oxid = :oxid", [
                ':oxid' => $articleId
            ]);
        }

        if ($parentArticleId && !$this->canDo($parentArticleId, $action)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function canUpdateField($field)
    {
        $canUpdate = true;

        if ($this->isDerived()) {
            $config = $this->getConfig();
            $canUpdate = false;
            $multiShopFields = $config->getConfigParam('aMultishopArticleFields');

            if (!$config->getConfigParam('blMallCustomPrice')) {
                return false;
            } elseif (in_array(strtoupper($field), $multiShopFields)) {
                $canUpdate = true;
            }
        }

        return $canUpdate ? parent::canUpdateField($field) : $canUpdate;
    }

    /**
     * Checks if any field can be updated from aMultishopArticleFields
     *
     * @return bool
     */
    public function canUpdateAnyField()
    {
        if ($this->_blCanUpdateAnyField === null) {
            $canUpdateAnyField = false;
            $config = $this->getConfig();
            $multiShopFields = $config->getConfigParam('aMultishopArticleFields');

            if (is_array($multiShopFields)) {
                foreach ($multiShopFields as $field) {
                    $canUpdateAnyField = $this->canUpdateField($field);
                    if ($canUpdateAnyField) {
                        break;
                    }
                }
            } else {
                // if aMultishopArticleFields array is empty, using previous implementation
                $canUpdateAnyField = $this->canUpdateField('oxprice');
            }
            $this->_blCanUpdateAnyField = $canUpdateAnyField;
        }

        return $this->_blCanUpdateAnyField;
    }

    /**
     * Checks if user rights allows to view/open current product
     *
     * @param string $articleId product id (optional)
     *
     * @return bool
     */
    public function canView($articleId = null)
    {
        $rights = $this->getRights();
        if ($this->isAdmin() || !$rights) {
            return true;
        }

        return $this->canDo($articleId, 1);
    }

    /**
     * Checks if user rights allows to buy current product
     *
     * @param string $articleId product id (optional)
     *
     * @return bool
     */
    public function canBuy($articleId = null)
    {
        $rights = $this->getRights();
        if ($this->isAdmin() || !$rights) {
            return true;
        }

        return $this->canDo($articleId, 2);
    }

    /**
     * @inheritdoc
     */
    public function isVisible()
    {
        if (!$this->canView()) {
            return false;
        }

        return parent::isVisible();
    }

    /**
     * @inheritdoc
     */
    public function assign($aRecord)
    {
        if (!$this->canRead()) {
            return false;
        }

        $result = parent::assign($aRecord);

        $this->_assignAccessRights();

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function delete($sOXID = null)
    {
        $sOXID = $sOXID ? $sOXID : $this->getId();

        if (!$sOXID) {
            return false;
        }

        if (!$this->canDelete($sOXID)) {
            return false;
        }

        $this->executeDependencyEvent(self::DEPENDENCY_EVENT_ARTICLE_DELETED);

        // delete self
        return parent::delete($sOXID);
    }

    /**
     * Checks if derived update is allowed:
     *  if - blMallCustomPrice = true, current shop id != product shop id, setupped aMultishopArticleFields - returns true
     *  else - returns parent::allowDerivedUpdate()
     * Task #1579 - impossible to edit price for inherited / assigned products
     *
     * @return bool
     */
    public function allowDerivedUpdate()
    {
        $config = $this->getConfig();
        if (
            $config->getConfigParam('blMallCustomPrice') && $this->oxarticles__oxshopid->value &&
            $config->getShopId() != $this->oxarticles__oxshopid->value &&
            is_array($config->getConfigParam('aMultishopArticleFields'))
        ) {
            return true;
        }

        return parent::allowDerivedUpdate();
    }

    /**
     * @inheritdoc
     */
    public function onChange($action = null, $articleId = null, $parentArticleId = null)
    {
        parent::onChange($action, $articleId, $parentArticleId);

        if (!isset($articleId)) {
            if ($this->getId()) {
                $articleId = $this->getId();
            }
            if (!isset($articleId)) {
                $articleId = $this->oxarticles__oxid->value;
            }
        }

        $config = $this->getConfig();

        // cache control ..
        $this->_resetCache($articleId);
        if ($action === ACTION_UPDATE_STOCK) {
            $this->executeDependencyEvent(self::DEPENDENCY_EVENT_STOCK_CHANGED);
        } elseif ($action === ACTION_DELETE) {
            $this->executeDependencyEvent(self::DEPENDENCY_EVENT_ARTICLE_DELETED);
        } else {
            $this->executeDependencyEvent();
        }

        if ($config->getConfigParam('blUseContentCaching')) {
            $genericCache = oxNew(ContentCache::class);
            $genericCache->reset();
        }
    }

    /**
     * Checks for VPE info which applies changes on passed amount
     *
     * @param double $amount Amount
     *
     * @return double
     */
    public function checkForVpe($amount)
    {
        $database = DatabaseProvider::getDb();
        $vpe = $database->getOne('select oxvpe from oxarticles where oxid = :oxid', [
            ':oxid' => $this->getId()
        ]);
        if ($vpe > 1) {
            // change amount
            $amount = ceil(($amount / $vpe));
            $amount = $amount * $vpe;
        }

        return $amount;
    }

    /**
     * @inheritdoc
     */
    public function getLongDescription()
    {
        if ($this->_oLongDesc === null) {
            if (!$this->canReadField('oxlongdesc')) {
                $this->_oLongDesc = new Field();
                return $this->_oLongDesc;
            }
        }

        return parent::getLongDescription();
    }

    /**
     * @inheritdoc
     */
    public function setArticleLongDesc($longDescription)
    {
        if (!$this->canUpdateField('oxlongdesc')) {
            return false;
        }

        return parent::setArticleLongDesc($longDescription);
    }

    /**
     * Execute cache dependencies
     *
     * @param int $dependencyEvent event name
     *
     * @return null
     */
    public function executeDependencyEvent($dependencyEvent = null)
    {
        if ($dependencyEvent == self::DEPENDENCY_EVENT_STOCK_CHANGED) {
            $this->executeDependencyEventAfterStockChanges();
        } else {
            $this->_updateSelfDependency();
            $this->_updateParentDependency();

            if ($dependencyEvent == self::DEPENDENCY_EVENT_ARTICLE_DELETED || $this->hasSortingFieldsChanged()) {
                $this->_updateCategoryDependency();
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function addToRatingAverage($rating)
    {
        parent::addToRatingAverage($rating);

        $this->_updateSelfDependency();
    }

    /**
     * @inheritdoc
     */
    public function getVendorId($forceReload = false)
    {
        $vendorId = parent::getVendorId();
        if ($vendorId) {
            if (!$forceReload && isset(self::$_aArticleVendors[$this->getId()])) {
                return self::$_aArticleVendors[$this->getId()];
            }
            $database = DatabaseProvider::getDb();
            $vendorView = getViewName('oxvendor');
            $vendorId = $database->getOne("select oxid from $vendorView where oxid = :oxid", [
                ':oxid' => $vendorId
            ]);
            self::$_aArticleVendors[$this->getId()] = $vendorId;
        }

        return $vendorId;
    }

    /**
     * @inheritdoc
     */
    public function getManufacturerId($forceReload = false)
    {
        $manufacturerId = parent::getManufacturerId();
        if ($manufacturerId) {
            if (!$forceReload && isset(self::$_aArticleManufacturers[$this->getId()])) {
                return self::$_aArticleManufacturers[$this->getId()];
            }
            $oDb = DatabaseProvider::getDb();
            $manufacturersView = getViewName('oxmanufacturers');
            $manufacturerId = $oDb->getOne("select oxid from $manufacturersView where oxid = :oxid", [
                ':oxid' => $manufacturerId
            ]);
            self::$_aArticleManufacturers[$this->getId()] = $manufacturerId;
        }

        return $manufacturerId;
    }

    /**
     * Execute cache dependencies:
     * Invalidates generic cache;
     * Flush cache when stock status has changed, otherwise nothing in views will change;
     * Runs article parent dependency event.
     */
    public function executeDependencyEventAfterStockChanges()
    {
        $genericCache = $this->_getCacheBackend();

        if ($genericCache->isActive()) {
            $genericCache->invalidate($this->getCacheKeys());
        }

        //
        if ($this->_isStockStatusChanged()) {
            $this->_updateArticleWidgetsDependency();

            // article either becomes not visible or visible
            if (!$this->isVisible() || $this->_isVisibilityChanged()) {
                $this->_updateOtherWidgetsDependency();
                $this->_updatePagesDependency();
                $this->_updateOtherPagesDependency();
                $this->_updateArticleObjectDependency();
                $this->_updateCategoryObjectsDependency();
            }
        }

        $article = $this->getParentArticle();
        if ($article) {
            $article->executeDependencyEvent(self::DEPENDENCY_EVENT_STOCK_CHANGED);
        }
    }

    /**
     * Generate cache key
     *
     * @param string $articleId id
     *
     * @return string
     */
    public function getCacheKey($articleId = null)
    {
        if (!$articleId) {
            $articleId = $this->getId();
        }

        return 'oxArticle_' . $articleId . '_' . $this->getConfig()->getShopId() . '_' . Registry::getLang()->getLanguageAbbr($this->getLanguage());
    }

    /**
     * Generate cache keys for dependent cached data.
     *
     * @param array $languages lang id array
     * @param array $shopIds   shop ids array
     *
     * @return string
     */
    public function getCacheKeys($languages = null, $shopIds = null)
    {
        $cacheKeys = array();
        $languages = $languages ? $languages : Registry::getLang()->getLanguageIds();
        $shopIds = $shopIds ? $shopIds : $this->getConfig()->getShopIds();

        foreach ($shopIds as $shopId) {
            foreach ($languages as $languageId) {
                $cacheKeys[] = 'oxArticle_' . $this->getId() . '_' . $shopId . '_' . $languageId;
            }
        }

        return $cacheKeys;
    }

    /**
     * Returns current article vendor object. If $blShopCheck = false, then
     * vendor loading will fallback to MultiLanguageModel object and blReadOnly parameter
     * will be set to true if vendor is not assigned to current shop
     *
     * @return MultiLanguageModel
     * @deprecated will be renamed to "createMultilanguageVendorObject" in next major
     */
    public function _createMultilanguageVendorObject() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $vendor = parent::_createMultilanguageVendorObject();
        $vendor->setForceCoreTableUsage(true);

        return $vendor;
    }

    /**
     * @inheritdoc
     */
    public function unassignFromShop($shopIds)
    {
        $result = parent::unassignFromShop($shopIds);

        if (!is_array($shopIds)) {
            $shopIds = array($shopIds);
        }

        //remove unused oxfield2shop values
        $field2Shop = $this->_getField2Shop();
        foreach ($shopIds as $sShopId) {
            $field2Shop->cleanMultishopFields($sShopId);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function updateManufacturerBeforeLoading($oManufacturer)
    {
        parent::updateManufacturerBeforeLoading($oManufacturer);

        $oManufacturer->setForceCoreTableUsage(true);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "selectCategoryIds" in next major
     */
    protected function _selectCategoryIds($query, $field) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $results = parent::_selectCategoryIds($query, $field);

        $category = oxNew(Category::class);

        foreach ($results as $key => $categoryId) {
            if (!$category->canView($categoryId)) {
                unset($results[$key]);
            }
        }

        return $results;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "saveArtLongDesc" in next major
     */
    protected function _saveArtLongDesc() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (in_array("oxlongdesc", $this->_aSkipSaveFields)) {
            return;
        }
        if ($this->isDerived() && !$this->canUpdateField("oxlongdesc")) {
            return;
        }
        parent::_saveArtLongDesc();
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "skipSaveFields" in next major
     */
    protected function _skipSaveFields() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_skipSaveFields();
        $config = $this->getConfig();

        // Also remove the fields which are not saved for subshops (these are defined in $myConfg->aMultishopArticleFields array).

        $shopId = $config->getShopID();
        $multiShopArticleFields = $config->getConfigParam('aMultishopArticleFields');

        // Removing multishop fields which should be saved before some another way.
        if ($config->getConfigParam('blMallCustomPrice') && $this->oxarticles__oxshopid->value && $shopId != $this->oxarticles__oxshopid->value && is_array($multiShopArticleFields)) {
            $languagesCount = count(Registry::getLang()->getLanguageIds());
            foreach ($multiShopArticleFields as $field) {
                $field = strtolower($field);
                $this->_aSkipSaveFields[] = $field;
                if ($this->isMultilingualField($field)) {
                    for ($ii = 1; $ii < $languagesCount; ++$ii) {
                        $this->_aSkipSaveFields[] = $field . "_$ii";
                    }
                }
            }
        }
    }

    /**
     * Sets shop specific article information from oxfield2shop table
     * (default are oxprice, oxpricea, oxpriceb, oxpricec
     * (specified in \OxidEsales\Eshop\Core\Config::aMultishopArticleFields param))
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $article Article object
     * @deprecated underscore prefix violates PSR12, will be renamed to "setShopValues" in next major
     */
    protected function _setShopValues($article) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $config = $this->getConfig();
        $shopId = $config->getShopID();
        $multishopArticleFields = $config->getConfigParam('aMultishopArticleFields');
        if ($config->getConfigParam('blMallCustomPrice') && $shopId != $article->oxarticles__oxshopid->value && is_array($multishopArticleFields)) {
            $field2Shop = oxNew(Field2Shop::class);
            $field2Shop->setEnableMultilang($this->_blEmployMultilanguage);
            $field2Shop->setLanguage($this->getLanguage());
            $field2Shop->setProductData($this);
        }
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "insert" in next major
     */
    protected function _insert() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $result = parent::_insert();

        //copy parent subshop assignments for a new variant
        if ($this->isVariant() && $this->getParentArticle() !== false) {
            $element2ShopRelations = $this->_getElement2ShopRelations();
            $element2ShopRelations->updateInheritanceFromParent($this->getId(), $this->getParentId());
        }

        return $result;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "update" in next major
     */
    protected function _update() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->canUpdate()) {
            return false;
        }

        $this->setUpdateSeo(true);
        $this->_setUpdateSeoOnFieldChange('oxtitle');
        $this->_skipSaveFields();

        $config = $this->getConfig();
        //saving custom subshop fields
        $shopId = $config->getShopId();
        $multishopArticleFields = $config->getConfigParam('aMultishopArticleFields');
        if ($config->getConfigParam('blMallCustomPrice') && $this->oxarticles__oxshopid->value && $shopId != $this->oxarticles__oxshopid->value && is_array($multishopArticleFields)) {
            $field2Shop = oxNew(Field2Shop::class);
            $field2Shop->setEnableMultilang($this->_blEmployMultilanguage);
            $field2Shop->setLanguage($this->getLanguage());

            return $field2Shop->saveProductData($this);
        }

        $result = parent::_update();

        $this->executeDependencyEvent();

        return $result;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "deleteRecords" in next major
     */
    protected function _deleteRecords($articleId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_deleteRecords($articleId);

        $database = DatabaseProvider::getDb();

        return $database->execute('delete from oxfield2shop where oxartid = :oxartid ', [
            ':oxartid' => $articleId
        ]);
    }

    /**
     * Return sub shop variant min price
     *
     * @return null
     * @deprecated underscore prefix violates PSR12, will be renamed to "getShopVarMinPrice" in next major
     */
    protected function _getShopVarMinPrice() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_getSubShopVarMinPrice();
    }

    /**
     * Return sub shop variant max price
     *
     * @return null
     * @deprecated underscore prefix violates PSR12, will be renamed to "getShopVarMaxPrice" in next major
     */
    protected function _getShopVarMaxPrice() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_getSubShopVarMaxPrice();
    }

    /**
     * Return sub shop variant min price.
     *
     * @return double|null
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSubShopVarMinPrice" in next major
     */
    protected function _getSubShopVarMinPrice() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $myConfig = $this->getConfig();
        $sShopId = $myConfig->getShopId();
        if ($this->getConfig()->getConfigParam('blMallCustomPrice') && $sShopId != $this->oxarticles__oxshopid->value) {
            $sPriceSuffix = $this->_getUserPriceSufix();
            $sSql = 'SELECT ';
            if ($sPriceSuffix != '' && $this->getConfig()->getConfigParam('blOverrideZeroABCPrices')) {
                $sSql .= 'MIN(IF(`oxfield2shop`.`oxprice' . $sPriceSuffix . '` = 0, `oxfield2shop`.`oxprice`, `oxfield2shop`.`oxprice' . $sPriceSuffix . '`)) AS `varminprice` ';
            } else {
                $sSql .= 'MIN(`oxfield2shop`.`oxprice' . $sPriceSuffix . '`) AS `varminprice` ';
            }
            $sSql .= ' FROM ' . getViewName('oxfield2shop') . ' AS oxfield2shop
                        INNER JOIN ' . $this->getViewName(true) . ' AS oxarticles ON `oxfield2shop`.`oxartid` = `oxarticles`.`oxid`
                        WHERE ' . $this->getSqlActiveSnippet(true) . '
                            AND ( `oxarticles`.`oxparentid` = :oxparentid )
                            AND ( `oxfield2shop`.`oxshopid` = :oxshopid )';
            $dPrice = DatabaseProvider::getDb()->getOne($sSql, [
                ':oxparentid' => $this->getId(),
                ':oxshopid' => $sShopId
            ]);
        }

        return $dPrice;
    }

    /**
     * Return sub shop variant max price.
     *
     * @return double|null
     * @deprecated underscore prefix violates PSR12, will be renamed to "getSubShopVarMaxPrice" in next major
     */
    protected function _getSubShopVarMaxPrice() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $myConfig = $this->getConfig();
        $sShopId = $myConfig->getShopId();
        if ($this->getConfig()->getConfigParam('blMallCustomPrice') && $sShopId != $this->oxarticles__oxshopid->value) {
            $sPriceSuffix = $this->_getUserPriceSufix();
            $sSql = 'SELECT ';
            if ($sPriceSuffix != '' && $this->getConfig()->getConfigParam('blOverrideZeroABCPrices')) {
                $sSql .= 'MAX(IF(`oxfield2shop`.`oxprice' . $sPriceSuffix . '` = 0, `oxfield2shop`.`oxprice`, `oxfield2shop`.`oxprice' . $sPriceSuffix . '`)) AS `varmaxprice` ';
            } else {
                $sSql .= 'MAX(`oxfield2shop`.`oxprice' . $sPriceSuffix . '`) AS `varmaxprice` ';
            }
            $sSql .= ' FROM ' . getViewName('oxfield2shop') . ' AS oxfield2shop
                        INNER JOIN ' . $this->getViewName(true) . ' AS oxarticles ON `oxfield2shop`.`oxartid` = `oxarticles`.`oxid`
                        WHERE ' . $this->getSqlActiveSnippet(true) . '
                            AND ( `oxarticles`.`oxparentid` = :oxparentid )
                            AND ( `oxfield2shop`.`oxshopid` = :oxshopid )';
            $dPrice = DatabaseProvider::getDb()->getOne($sSql, [
                ':oxparentid' => $this->getId(),
                ':oxshopid' => $sShopId
            ]);
        }

        return $dPrice;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadData" in next major
     */
    protected function _loadData($articleId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return ($this->_getCacheBackend()->canLoadDataFromCacheBackend())
            ? $this->_loadFromCache($articleId)
            : parent::_loadData($articleId);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "getModifiedAmountPrice" in next major
     */
    protected function _getModifiedAmountPrice($amount) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $price = parent::_getModifiedAmountPrice($amount);

        $price = $this->_modifyMallPrice($price);

        return $price;
    }

    /**
     * Returns SQL select string with checks if items is accessible by R&R config
     *
     * @param bool $forceCoreTable forces core table usage (optional)
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "createSqlActiveSnippet" in next major
     */
    protected function _createSqlActiveSnippet($forceCoreTable) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $querySnippet = parent::_createSqlActiveSnippet($forceCoreTable);

        return $querySnippet . $this->_getSqlRightsSnippet($forceCoreTable);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "calculateVarMinPrice" in next major
     */
    protected function _calculateVarMinPrice() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $dPrice = parent::_calculateVarMinPrice();

        return $this->_modifyMallPrice($dPrice);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "prepareModifiedPrice" in next major
     */
    protected function _prepareModifiedPrice($dPrice) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $dPrice = parent::_prepareModifiedPrice($dPrice);
        $dPrice = $this->_modifyMallPrice($dPrice);

        return $dPrice;
    }

    /**
     * Returns oxCacheBackend from Registry
     *
     * @return Cache
     * @deprecated underscore prefix violates PSR12, will be renamed to "getCacheBackend" in next major
     */
    protected function _getCacheBackend() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return Registry::get(Cache::class);
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromDb" in next major
     */
    protected function _loadFromDb($articleId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $forceCoreTableUsage = $this->getForceCoreTableUsage();
        $this->_forceCoreTableUsageForSharedBasket();

        $data = parent::_loadFromDb($articleId);

        $this->setForceCoreTableUsage($forceCoreTableUsage);

        return $data;
    }

    /**
     * Sets forcing of core table usage for creating table view name when shared basket is enabled.
     */
    private function _forceCoreTableUsageForSharedBasket() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->getConfig()->getConfigParam('blMallSharedBasket')) {
            $this->setForceCoreTableUsage(true);
        }
    }

    /**
     * Load data from cache
     *
     * @param string $articleId id
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromCache" in next major
     */
    protected function _loadFromCache($articleId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $genericCache = $this->_getCacheBackend();
        $cacheKey = $this->getCacheKey($articleId);
        $cacheItem = $genericCache->get($cacheKey);

        if ($cacheItem) {
            $data = $cacheItem->getData();
        }

        if (!$data) {
            $data = $this->_loadFromDb($articleId);
            $cacheItem = oxNew(CacheItem::class);
            $cacheItem->setData($data);
            $genericCache->set($cacheKey, $cacheItem);
        }

        return $data;
    }

    /**
     * Resets article cache
     *
     * @param string $articleId Article ID
     *
     * @return null
     * @deprecated underscore prefix violates PSR12, will be renamed to "resetCache" in next major
     */
    protected function _resetCache($articleId = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $config = $this->getConfig();
        // if is not admin and caching enabled, try to clear it
        if (!$this->isAdmin() && $config->getConfigParam('blUseContentCaching')) {
            // stock control check
            $config = $this->getConfig();
            $articleId = $articleId ? $articleId : $this->getId();

            $invalidArticles = array();
            $invalidCategories = array();

            if ($articleId && $config->getConfigParam('blUseStock')) {
                // if active product id does not match reset product id..
                if ($articleId != $this->getId()) {
                    $product = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
                    $product->setSkipAssign(true);
                    $product->load($articleId);
                    $product->_assignStock();

                    return $product->_resetCache();
                }

                $contentCache = oxNew(ContentCache::class);
                $database = DatabaseProvider::getDb();
                // Fixing oxarticle unit test testResetCache().
                // Fetch mode is global so it might be easly lost to no assoc.
                $database->setFetchMode(DatabaseProvider::FETCH_MODE_ASSOC);

                // choosing parent id
                $query = "select oxparentid from oxarticles where oxid = :oxid";
                $parentId = $this->getId() ? $this->oxarticles__oxparentid->value : $database->getOne($query, [
                    ':oxid' => $articleId
                ]);

                // if article belongs to some action - deleting all cache
                $query = 'select 1 from oxactions2article where oxartid = :oxartid';
                if ($database->getOne($query, ['oxartid' => $parentId ? $parentId : $articleId])) {
                    $contentCache->reset();

                    return;
                }

                // this check can only be performed on loaded article ..
                if (($articleId = $this->getId()) && $this->oxarticles__oxstockflag->value != 4) {
                    // Stock control is ON

                    // GREEN light
                    $stockStatus = 0;

                    $stock = $this->_blNotBuyableParent ? $this->oxarticles__oxvarstock->value : $this->oxarticles__oxstock->value;

                    // ORANGE light
                    if ($stock <= $config->getConfigParam('sStockWarningLimit') && $stock > 0) {
                        $stockStatus = 1;
                    }

                    // RED light
                    if ($stock <= 0) {
                        $stockStatus = -1;
                    }

                    // checking initial with current (set by user) stock status ..
                    if ($this->_iStockStatus != $stockStatus) {
                        $params = [
                            ':oxobjectid' => $articleId
                        ];

                        // similar articles ...
                        if ($config->getConfigParam('bl_perfLoadSimilar')) {
                            $query = "from oxobject2attribute where oxobjectid != :oxobjectid and oxattrid
                                   in ( select oxattrid from oxobject2attribute where oxobjectid = :oxobjectid ) group by oxobjectid ";

                            // resetting cache fully if similar article count > 100
                            if (((int) $database->getOne("select count(*) from ( select count(oxobjectid) $query ) as _cnt", $params)) > $this->_iMaxSimilarForCacheReset) {
                                $contentCache->reset();

                                return;
                            }
                            $queryResult = $database->select("select oxobjectid $query", $params);
                            if ($queryResult != false && $queryResult->count() > 0) {
                                while (!$queryResult->EOF) {
                                    $resetOn[$queryResult->fields['oxobjectid']] = 'anid';
                                    $queryResult->fetchRow();
                                    $invalidArticles[] = $queryResult->fields['oxobjectid'];
                                }
                            }
                        }

                        // resetting self
                        $resetOn[$articleId] = 'anid';

                        // resetting parent if available
                        if ($parentId) {
                            $resetOn[$parentId] = 'anid';
                            $invalidArticles[] = $parentId;
                        }

                        // reset cache for article categories
                        $categoryIds = $this->getCategoryIds();
                        foreach ($categoryIds as $categoryId) {
                            $resetOn[$categoryId] = 'cid';
                            $invalidCategories[] = $categoryId;
                        }

                        $query = '';
                        if ($config->getConfigParam('bl_perfLoadCrossselling')) {
                            $query = "select oxaccessoire2article.oxarticlenid as _oxid from oxaccessoire2article
                                   where oxaccessoire2article.oxobjectid = :oxobjectid ";
                        }

                        if ($config->getConfigParam('bl_perfLoadAccessoires')) {
                            $query .= $query ? ' union ' : '';
                            $query .= "select oxobject2article.oxobjectid as _oxid from oxobject2article where oxobject2article.oxarticlenid = :oxobjectid
                                    union select oxobject2article.oxarticlenid as _oxid from oxobject2article where oxobject2article.oxobjectid = :oxobjectid group by _oxid";
                        }

                        if ($query) {
                            $queryResult = $database->select($query, $params);
                            if ($queryResult != false && $queryResult->count() > 0) {
                                while (!$queryResult->EOF) {
                                    $resetOn[$queryResult->fields['_oxid']] = 'anid';
                                    $queryResult->fetchRow();
                                    $invalidArticles[] = $queryResult->fields['_oxid'];
                                }
                            }
                        }

                        // resetting ..
                        $contentCache->resetOn($resetOn);
                    }
                }
            }
        }

        parent::_resetCache($articleId);
    }

    /**
     * Execute cache dependencies for parent
     * @deprecated underscore prefix violates PSR12, will be renamed to "updateParentDependency" in next major
     */
    protected function _updateParentDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $article = $this->getParentArticle();
        if ($article) {
            $article->executeDependencyEvent();
        }
    }

    /**
     * Execute cache dependencies by self
     * @deprecated underscore prefix violates PSR12, will be renamed to "updateSelfDependency" in next major
     */
    protected function _updateSelfDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $genericCache = $this->_getCacheBackend();

        if ($genericCache->isActive()) {
            //invalidate cache
            $genericCache->invalidate($this->getCacheKeys());
        }

        $this->_updateArticleWidgetsDependency();
        $this->_updateOtherWidgetsDependency();
        $this->_updatePagesDependency();

        // flush only when stock status has changed, otherwise nothing in views will change
        if ($this->_isStockStatusChanged()) {
            $this->_updateOtherPagesDependency();
        }
    }

    /**
     * Execute cache dependencies with categories
     * @deprecated underscore prefix violates PSR12, will be renamed to "updateCategoryDependency" in next major
     */
    protected function _updateCategoryDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        //category tree cache dependency
        $this->setForceCoreTableUsage(true);
        $categoriesIds = $this->getCategoryIds(false, true);
        $this->setForceCoreTableUsage(false);

        $category = oxNew(Category::class);
        $category->executeDependencyEvent($categoriesIds, false);
    }

    /**
     * Execute cache dependencies for article widgets.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    protected function _updateArticleWidgetsDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
    }

    /**
     * Execute cache dependencies for other widgets.
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    protected function _updateOtherWidgetsDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
    }

    /**
     * Execute cache dependencies for pages.
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    protected function _updatePagesDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
    }

    /**
     * Execute cache dependencies for other pages.
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    protected function _updateOtherPagesDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
    }

    /**
     * Execute cache dependencies for article object.
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    protected function _updateArticleObjectDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
    }

    /**
     * Execute cache dependencies for category objects.
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    protected function _updateCategoryObjectsDependency() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
    }

    /**
     * Modifies price according to special sub shop addition
     *
     * @param double $price Modifiable price
     *
     * @return double
     * @deprecated underscore prefix violates PSR12, will be renamed to "modifyMallPrice" in next major
     */
    protected function _modifyMallPrice(&$price) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $config = $this->getConfig();
        // mall add price stuff
        // MALL ON
        if ($config->isMall() && !$this->isAdmin()) {
            //adding shop addition
            if ($config->getConfigParam('iMallPriceAddition')) {
                if ($config->getConfigParam('blMallPriceAdditionPercent')) {
                    $price += Price::percent($price, $config->getConfigParam('iMallPriceAddition'));
                } else {
                    $price += $config->getConfigParam('iMallPriceAddition');
                }
            }
        }

        return $price;
    }

    /**
     * Assigns rr to article object
     * @deprecated underscore prefix violates PSR12, will be renamed to "assignAccessRights" in next major
     */
    protected function _assignAccessRights() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (!$this->isAdmin() && $this->getRights()) {
            // R&R: checking if access rights allows to buy this product
            if (!$this->_blNotBuyable) {
                $this->setBuyableState($this->canBuy());
            }
        }
    }

    /**
     * Gets shop relations object.
     *
     * @return Article2ShopRelations
     * @deprecated underscore prefix violates PSR12, will be renamed to "getElement2ShopRelations" in next major
     */
    protected function _getElement2ShopRelations() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (is_null($this->_oElement2ShopRelations)) {
            $this->_oElement2ShopRelations = oxNew(Article2ShopRelations::class, $this->getCoreTableName());
        }

        return $this->_oElement2ShopRelations;
    }

    /**
     * Returns Field2Shop object
     *
     * @return Field2Shop
     * @deprecated underscore prefix violates PSR12, will be renamed to "getField2Shop" in next major
     */
    protected function _getField2Shop() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return oxNew(Field2Shop::class);
    }

    /**
     * @inheritdoc
     */
    protected function updateVariantsBaseObject($baseObject, $forceCoreTableUsage = null)
    {
        parent::updateVariantsBaseObject($baseObject, $forceCoreTableUsage);

        $baseObject->setForceCoreTableUsage($forceCoreTableUsage);
    }
}
