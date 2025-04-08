<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

/**
 * @inheritdoc
 */
class RecommendationList extends \OxidEsales\EshopProfessional\Application\Model\RecommendationList
{
    /**
     * Execute cache dependencies
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     *
     */
    public function executeDependencyEvent()
    {
    }

    /**
     * @inheritdoc
     */
    public function removeArticle($oxId)
    {
        if ($oxId) {
            $this->executeDependencyEvent();
        }
        parent::removeArticle($oxId);
    }

    /**
     * @inheritdoc
     */
    public function addArticle($oxId, $description)
    {
        if ($oxId) {
            $this->executeDependencyEvent();
        }

        return parent::addArticle($oxId, $description);
    }

    /**
     * @inheritdoc
     */
    protected function onSave()
    {
        parent::onSave();
        $this->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    protected function onDelete()
    {
        parent::onDelete();
        $this->executeDependencyEvent();
    }
}
