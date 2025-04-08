<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\Registry;
use oxDb;

/**
 * @inheritdoc
 */
class SelectList extends \OxidEsales\EshopProfessional\Application\Model\SelectList
{

    /**
     * Returns article ids assigned to discount.
     *
     * @return array
     */
    public function getArticleIds()
    {
        return \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getCol("SELECT `oxobjectid` FROM `oxobject2selectlist` WHERE `oxselnid` = :oxselnid", [
            ':oxselnid' => $this->getId()
        ]);
    }

    /**
     * Execute cache dependencies.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    public function executeDependencyEvent()
    {
    }

    /**
     * Save this Object to database, insert or update as needed.
     *
     * @return string|bool
     */
    public function save()
    {
        $isSaved = parent::save();
        $this->executeDependencyEvent();

        return $isSaved;
    }
}
