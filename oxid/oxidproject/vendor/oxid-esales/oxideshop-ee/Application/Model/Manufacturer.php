<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class Manufacturer extends \OxidEsales\EshopProfessional\Application\Model\Manufacturer
{
    /**
     * @inheritdoc
     * Call cache flushing event.
     */
    public function delete($sOXID = null)
    {
        $this->executeDependencyEvent();

        return parent::delete($sOXID);
    }

    /**
     * @inheritdoc
     * Call cache flushing event.
     */
    public function save()
    {
        $this->executeDependencyEvent();
        $blSaved = parent::save();

        return $blSaved;
    }

    /**
     * Set pages to be flushed to cache.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     *
     * @return null
     */
    public function executeDependencyEvent()
    {
    }
}
