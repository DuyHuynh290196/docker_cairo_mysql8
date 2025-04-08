<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class Content extends \OxidEsales\EshopProfessional\Application\Model\Content
{
    /**
     * returns oxCacheBackend from Registry
     *
     * @return oxCacheBackend
     * @deprecated underscore prefix violates PSR12, will be renamed to "getCacheBackend" in next major
     */
    protected function _getCacheBackend() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\Cache\Generic\Cache::class);
    }

    /**
     * Load data from cache
     *
     * @param string $loadId id
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromCache" in next major
     */
    protected function _loadFromCache($loadId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oCache = $this->_getCacheBackend();
        $sKey = $this->getCacheKey($loadId);
        $oCacheItem = $oCache->get($sKey);

        if ($oCacheItem) {
            $aData = $oCacheItem->getData();
        } else {
            $aData = $this->_loadFromDb($loadId);
            $oCacheItem = oxNew(\OxidEsales\Eshop\Core\Cache\Generic\CacheItem::class);
            $oCacheItem->setData($aData);
            $oCache->set($sKey, $oCacheItem);
        }

        return $aData;
    }

    /**
     * Generate cache key
     *
     * @param string $sLoadId load id
     *
     * @return string
     */
    public function getCacheKey($sLoadId = null)
    {
        if (!$sLoadId) {
            // When load indent don't set load it from db, because on savewe can change it
            $sLoadId = \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne("SELECT `oxloadid` FROM `oxcontents` WHERE `oxid` = :oxid", [
                ':oxid' => $this->getId()
            ]);
        }

        return 'oxContent_' . $sLoadId . '_' . $this->getConfig()->getShopId() . '_' . \OxidEsales\Eshop\Core\Registry::getLang()->getLanguageAbbr($this->getLanguage());
    }

    /**
     * @inheritdoc
     */
    public function loadByIdent($loadId, $onlyActive = false)
    {
        $oCache = $this->_getCacheBackend();

        if (!$this->isAdmin() && $oCache->isActive()) {
            $result = $this->assignContentData($this->_loadFromCache($loadId), $onlyActive);
        } else {
            $result = parent::loadByIdent($loadId, $onlyActive);
        }

        return $result;
    }

    /**
     * Execute cache dependencies.
     */
    public function executeDependencyEvent()
    {
        $this->_updateSelfDependencies();
        $this->_updateContentListDependencies();
    }

    /**
     * Execute cache dependencies by self.
     * @deprecated will be renamed to "updateSelfDependencies" in next major
     */
    public function _updateSelfDependencies() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oCache = $this->_getCacheBackend();
        if ($oCache->isActive()) {
            //invalidate cache
            $oCache->invalidate($this->getCacheKey());
        }
    }

    /**
     * Execute cache dependencies.
     * @deprecated will be renamed to "updateContentListDependencies" in next major
     */
    public function _updateContentListDependencies() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oCache = $this->_getCacheBackend();
        if ($oCache->isActive()) {
            //category tree cache dependency
            $oList = oxNew(\OxidEsales\Eshop\Application\Model\ContentList::class);
            $oList->executeDependencyEvent($this->getType());
        }
    }

    /**
     * @inheritdoc
     */
    public function assign($dbRecord)
    {
        if (!$this->canRead()) {
            return false;
        } else {
            return parent::assign($dbRecord);
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($oxid = null)
    {
        $this->executeDependencyEvent();

        return parent::delete($oxid);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $this->executeDependencyEvent();

        return parent::save();
    }
}
