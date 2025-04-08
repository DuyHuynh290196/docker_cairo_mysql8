<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class Delivery extends \OxidEsales\EshopProfessional\Application\Model\Delivery
{
    /**
     * @inheritdoc
     */
    public function delete($oxid = null)
    {
        if (!$oxid) {
            $oxid = $this->getId();
        }

        if (!$this->canDelete($oxid)) {
            return false;
        }

        return parent::delete($oxid);
    }
}
