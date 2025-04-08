<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 * @deprecated 6.5.3 "News" feature will be removed completely
 */
class News extends \OxidEsales\EshopProfessional\Application\Model\News
{
    /**
     * @inheritdoc
     * Check if has rights to work with this object.
     */
    public function assign($dbRecord)
    {
        if (!$this->canRead()) {
            return false;
        }

        parent::assign($dbRecord);
    }

    /**
     * @inheritdoc
     * Call cache flushing event.
     */
    public function delete($sOxid = null)
    {
        if (!$sOxid) {
            $sOxid = $this->getId();
        }
        if (!$sOxid) {
            return false;
        }

        $result = parent::delete($sOxid);

        $this->executeDependencyEvent();

        return $result;
    }

    /**
     * @inheritdoc
     * Call cache flushing event.
     * @deprecated underscore prefix violates PSR12, will be renamed to "update" in next major
     */
    protected function _update() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        parent::_update();
        $this->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     * Call cache flushing event.
     * @deprecated underscore prefix violates PSR12, will be renamed to "insert" in next major
     */
    protected function _insert() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $result = parent::_insert();

        $this->executeDependencyEvent();

        return $result;
    }

    /**
     * Set pages to be flushed to cache.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    public function executeDependencyEvent()
    {
    }
}
