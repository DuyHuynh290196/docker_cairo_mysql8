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
class Vendor extends \OxidEsales\EshopProfessional\Application\Model\Vendor
{
    /**
     * @inheritdoc
     */
    public function delete($oxid = null)
    {
        $this->executeDependencyEvent();

        return parent::delete($oxid);
    }

    /**
     * Execute cache dependencies
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     *
     * @return null
     */
    public function executeDependencyEvent()
    {
    }

    /**
     * Save this Object to database, insert or update as needed.
     *
     * @return mixed
     */
    public function save()
    {
        $parentResult = parent::save();
        $this->executeDependencyEvent();

        return $parentResult;
    }
}
