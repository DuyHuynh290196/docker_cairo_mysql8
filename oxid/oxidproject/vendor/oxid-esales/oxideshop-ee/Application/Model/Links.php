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
class Links extends \OxidEsales\EshopProfessional\Application\Model\Links
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
     * Delete Object from database and remove dependencies.
     *
     * @param string $sOXID Object ID (default null)
     *
     * @return mixed
     */
    public function delete($sOXID = null)
    {
        if (!$sOXID) {
            $sOXID = $this->getId();
        }
        if (!$sOXID) {
            return false;
        }
        $blDeleted = parent::delete($sOXID);
        $this->executeDependencyEvent();

        return $blDeleted;
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
