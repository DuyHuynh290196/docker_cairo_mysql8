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
class Discount extends \OxidEsales\EshopProfessional\Application\Model\Discount
{
    /**
     * @inheritdoc
     */
    public function delete($sOXID = null)
    {
        if (!$sOXID) {
            $sOXID = $this->getId();
        }

        if (!$this->canDelete($sOXID)) {
            return false;
        }

        $this->executeDependencyEvent();

        return parent::delete($sOXID);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $this->executeDependencyEvent();

        return parent::save();
    }

    /**
     * Execute cache dependencies
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    public function executeDependencyEvent()
    {
    }
}
