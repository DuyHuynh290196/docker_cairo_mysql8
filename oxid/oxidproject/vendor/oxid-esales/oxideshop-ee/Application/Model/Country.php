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
class Country extends \OxidEsales\EshopProfessional\Application\Model\Country
{
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
        $wasSaved = parent::save();
        $this->executeDependencyEvent();

        return $wasSaved;
    }

    /**
     * delete Object from database
     *
     * @param string $oxid Object ID (default null)
     *
     * @return mixed
     */
    public function delete($oxid = null)
    {
        if (!$oxid) {
            $oxid = $this->getId();
        }
        if (!$oxid) {
            return false;
        }
        $wasDeleted = parent::delete($oxid);
        $this->executeDependencyEvent();

        return $wasDeleted;
    }
}
