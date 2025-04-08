<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class DeliverySet extends \OxidEsales\EshopProfessional\Application\Model\DeliverySet
{
    /**
     * @inheritdoc
     */
    public function delete($sOxId = null)
    {
        if (!$sOxId) {
            $sOxId = $this->getId();
        }

        if (!$this->canDelete($sOxId)) {
            return false;
        }

        return parent::delete($sOxId);
    }
}
