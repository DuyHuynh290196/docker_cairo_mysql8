<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class Attribute extends \OxidEsales\EshopProfessional\Application\Model\Attribute
{
    /**
     * @inheritdoc
     */
    protected function canDeleteAttribute($oxId)
    {
        $canDelete = parent::canDeleteAttribute($oxId);
        if (!$canDelete || !$this->canDelete($oxId)) {
            $canDelete = false;
        }

        return $canDelete;
    }

    /**
     * Returns article ids assigned to attribute.
     *
     * @return array
     */
    public function getArticleIds()
    {
        $sViewName = getViewName('oxobject2attribute');

        return \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getCol("SELECT `oxobjectid` FROM $sViewName WHERE `oxattrid` = :oxattrid and `oxvalue` != '' ", [
            ':oxattrid' => $this->getId()
        ]);
    }

    /**
     * Execute cache dependencies.
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     */
    public function executeDependencyEvent()
    {
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        $isSaved = parent::save();
        $this->executeDependencyEvent();

        return $isSaved;
    }
}
