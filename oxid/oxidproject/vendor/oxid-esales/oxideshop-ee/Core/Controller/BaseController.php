<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Controller;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopProfessional\Core\Controller\BaseController as PEBaseController;

/** @inheritdoc */
class BaseController extends PEBaseController
{
    private const DEFAULT_CACHE_LIFETIME = 3600;
    /**
     * Sets if page should be cached if user is logged in
     *
     * @var bool
     */
    protected $_blCacheForUser = true;

    /**
     * Sets if page should be cached
     *
     * @var bool
     */
    protected $_blCachePage = true;

    /**
     * Allow invalidating cache for shop control, but not for widget controller.
     *
     * @var bool
     */
    protected $_blAllowCacheInvalidating = true;

    /**
     * Returns if shop is mall
     *
     * @return bool
     */
    public function isMall()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function executeFunction($functionName)
    {
        if ($functionName && !self::$_blExecuted) {
            if (method_exists($this, $functionName)) {
                // once again checking if user has enough rights to exec. preferred action
                if (($rights = $this->getRights())) {
                    $rights->processView($this, $functionName);
                }
            }
        }

        parent::executeFunction($functionName);
    }

    /**
     * Returns if view should be cached
     *
     * @return bool
     */
    public function isCacheable()
    {
        if (!$this->_blCacheForUser && $this->getUser()) {
            return false;
        }

        return $this->_blCachePage;
    }

    /**
     * Returns if view should be cached
     *
     * @param bool $cache set/unset to cache page
     */
    public function setIsCacheable($cache)
    {
        $this->_blCachePage = $cache;
    }

    /**
     * @return int
     * @throws DatabaseConnectionException
     */
    public function getCacheLifeTime()
    {
        $defaultLifetime = $this->getDefaultCacheLifetime();
        $databaseValues = $this->fetchMinimalLifetimesFromAssociatedTables();
        $minimalLifetime = $databaseValues
            ? $this->chooseMinimal(min($databaseValues), $defaultLifetime)
            : $defaultLifetime;
        /** basket can be reserved for private sales */
        if (!$this->isBasketLifetimeUsed()) {
            return $minimalLifetime;
        }
        $basketMinimalLifetime = $this->applyBasketReservationTimeoutToMinimalLifetime(
            $this->fetchMinimalLifetimeFromBasketReservationsTable()
        );
        return $this->chooseMinimal($minimalLifetime, $basketMinimalLifetime);
    }

    /**
     * Sets allow cache invalidating.
     *
     * @param bool $allowInvalidation Cache invalidating
     */
    public function setAllowCacheInvalidating($allowInvalidation)
    {
        $this->_blAllowCacheInvalidating = $allowInvalidation;
    }

    /**
     * Gets allow cache invalidating.
     *
     * @return bool
     */
    public function getAllowCacheInvalidating()
    {
        return $this->_blAllowCacheInvalidating;
    }

    /**
     * @return bool
     * @throws DatabaseConnectionException
     */
    private function isBasketLifetimeUsed(): bool
    {
        if (!$this->getConfigValue('blPsBasketReservationEnabled')) {
            return false;
        }
        $basketDatabaseValue = $this->fetchMinimalLifetimeFromBasketReservationsTable();
        if (!$basketDatabaseValue) {
            return false;
        }
        return $this->applyBasketReservationTimeoutToMinimalLifetime($basketDatabaseValue) > 0;
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getConfigValue(string $key)
    {
        return Registry::getConfig()->getConfigParam($key);
    }

    /**
     * @param int $val1
     * @param int $val2
     * @return int
     */
    private function chooseMinimal(int $val1, int $val2): int
    {
        return $val1 < $val2 ? $val1 : $val2;
    }

    /** @return int|mixed */
    private function getDefaultCacheLifetime()
    {
        return $this->getConfigValue("iLayoutCacheLifeTime") ?: self::DEFAULT_CACHE_LIFETIME;
    }

    /**
     * @return array
     * @throws DatabaseConnectionException
     */
    private function fetchMinimalLifetimesFromAssociatedTables(): array
    {
        $values = [];
        $timestamp = Registry::getUtilsDate()->formatDBTimestamp(Registry::getUtilsDate()->getTime());
        foreach ($this->collectMinimalLifetimeQueries() as $query) {
            $value = (int)DatabaseProvider::getDb()->getOne($query, [':now_str' => $timestamp]);
            if ($value) {
                $values[] = $value;
            }
        }
        return $values;
    }

    /**
     * @return int
     * @throws DatabaseConnectionException
     */
    private function fetchMinimalLifetimeFromBasketReservationsTable(): int
    {
        return (int)DatabaseProvider::getDb()->getOne(
            $this->getBasketReservationsUpdateTimeSelectQuery(),
            [':now_int' => Registry::getUtilsDate()->getTime()]
        );
    }

    /**
     * @param int $minimalLifetime
     * @return int
     */
    private function applyBasketReservationTimeoutToMinimalLifetime(int $minimalLifetime): int
    {
        return $this->getConfigValue('iPsBasketReservationTimeout') - $minimalLifetime;
    }

    /** @return array */
    private function collectMinimalLifetimeQueries(): array
    {
        $queries = [
            $this->getDiscountActiveFromTimeSelectQuery(),
            $this->getArticlesUpdatePriceTimeSelectQuery(),
            $this->getField2ShopUpdatePriceTimeSelectQuery(),
            $this->getActionsActiveToTimeSelectQuery(),
        ];
        if ($this->getConfigValue('blUseTimeCheck')) {
            $queries[] = $this->getArticlesActiveFromTimeSelectQuery();
        }
        return $queries;
    }

    /** @return string */
    private function getDiscountActiveFromTimeSelectQuery(): string
    {
        return 'SELECT UNIX_TIMESTAMP(MIN(`oxactivefrom`)) - UNIX_TIMESTAMP(:now_str)
            FROM `oxdiscount`
            WHERE `oxactivefrom` > :now_str';
    }

    /** @return string */
    private function getArticlesUpdatePriceTimeSelectQuery(): string
    {
        return 'SELECT UNIX_TIMESTAMP(MIN(`oxupdatepricetime`)) - UNIX_TIMESTAMP(:now_str)
            FROM `oxarticles`
            WHERE `oxupdatepricetime` > :now_str';
    }

    /** @return string */
    private function getField2ShopUpdatePriceTimeSelectQuery(): string
    {
        return 'SELECT UNIX_TIMESTAMP(MIN(`oxupdatepricetime`)) - UNIX_TIMESTAMP(:now_str)
            FROM `oxfield2shop`
            WHERE `oxupdatepricetime` > :now_str';
    }

    /** @return string */
    private function getActionsActiveToTimeSelectQuery(): string
    {
        return 'SELECT UNIX_TIMESTAMP(MIN(`oxactiveto`)) - UNIX_TIMESTAMP(:now_str)
            FROM `oxactions`
            WHERE `oxactiveto` > :now_str';
    }

    /** @return string */
    private function getArticlesActiveFromTimeSelectQuery(): string
    {
        return 'SELECT UNIX_TIMESTAMP(MIN(`oxactivefrom`)) - UNIX_TIMESTAMP(:now_str)
            FROM `oxarticles`
            WHERE `oxactivefrom` > :now_str';
    }

    /** @return string */
    private function getBasketReservationsUpdateTimeSelectQuery(): string
    {
        return "SELECT :now_int - MIN(`oxupdate`)
            FROM `oxuserbaskets`
            WHERE `oxupdate` != '0' AND `oxupdate` < :now_int AND `oxtitle` = 'reservations'";
    }
}
