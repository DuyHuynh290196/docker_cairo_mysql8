<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Component;

use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class BasketComponent extends \OxidEsales\EshopProfessional\Application\Component\BasketComponent
{
    /**
     * @inheritdoc
     */
    public function toBasket($sProductId = null, $dAmount = null, $aSel = null, $aPersParam = null, $blOverride = false)
    {
        $redirectUrl = parent::toBasket($sProductId, $dAmount, $aSel, $aPersParam, $blOverride);

        if (!is_null($redirectUrl)) {
            $this->_flushPrivateSalesCategories();
        }

        return $redirectUrl;
    }

    /**
     * @inheritdoc
     */
    public function executeUserChoice()
    {
        $this->_flushPrivateSalesCategories();
        return parent::executeUserChoice();
    }

    /**
     * Flushes Private sales messages in cached category list
     * Called when adding items to basket and private sales messages in different categories are cached.
     *
     * @deprecated since 2019-11-23 (6.3.0 component version). All calls of the method will be removed soon.
     */
    protected function _flushPrivateSalesCategories() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
    }

    /**
     * @inheritdoc
     */
    protected function emptyBasket($oBasket)
    {
        parent::emptyBasket($oBasket);

        if ($oParent = $this->getParent()) {
            $oParent->setAllowCacheInvalidating(true);
        }
    }
}
