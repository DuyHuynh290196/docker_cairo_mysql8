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
class MediaUrl extends \OxidEsales\EshopProfessional\Application\Model\MediaUrl
{
    /**
     * @inheritdoc
     * Call cache flushing event.
     */
    public function save()
    {
        $blSaved = parent::save();
        $this->executeDependencyEvent();

        return $blSaved;
    }

    /**
     * @inheritdoc
     * Call cache flushing event.
     */
    public function delete($sOXID = null)
    {
        $result = parent::delete($sOXID);

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
