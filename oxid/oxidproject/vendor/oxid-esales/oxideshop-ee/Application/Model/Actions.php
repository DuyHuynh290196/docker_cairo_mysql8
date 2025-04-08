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
class Actions extends \OxidEsales\EshopProfessional\Application\Model\Actions
{
    /**
     * @inheritdoc
     */
    public function addArticle($articleId)
    {
        parent::addArticle($articleId);

        $this->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function removeArticle($articleId)
    {
        $removedArticles = parent::removeArticle($articleId);

        $this->executeDependencyEvent();

        return $removedArticles;
    }

    /**
     * @inheritdoc
     */
    public function delete($articleId = null)
    {
        $articleId = $articleId ? $articleId : $this->getId();
        if (!$articleId || !$this->canDelete($articleId)) {
            return false;
        }
        $isDeleted = parent::delete($articleId);

        $this->executeDependencyEvent();

        return $isDeleted;
    }

    /**
     * @inheritdoc
     */
    public function start()
    {
        parent::start();

        $this->executeDependencyEvent();
    }

    /**
     * @inheritdoc
     */
    public function stop()
    {
        parent::stop();

        $this->executeDependencyEvent();
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

    /**
     * Execute cache dependencies.
     *
     * @deprecated since 2019-02-22 (6.3.0 component version). All calls of the method will be removed soon.
     *
     * @return null
     */
    public function executeDependencyEvent()
    {
    }
}
