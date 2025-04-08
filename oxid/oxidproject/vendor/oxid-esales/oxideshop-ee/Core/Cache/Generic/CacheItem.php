<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\Generic;

/**
 * Cached data class
 */
class CacheItem
{
    /** @var mixed Data storage */
    protected $_data = null;

    /**
     * Set data to cache item.
     *
     * @param mixed $mData data
     */
    public function setData($mData)
    {
        $this->_data = $mData;
    }

    /**
     * Get data from item.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }
}
