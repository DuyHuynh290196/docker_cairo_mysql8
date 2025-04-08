<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class OrderMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\OrderMain
{
    /**
     * @inheritdoc
     */
    protected function onOrderSend()
    {
        parent::onOrderSend();
        $this->setParameterToReloadFrame();
    }

    /**
     * @inheritdoc
     */
    protected function onOrderReset()
    {
        parent::onOrderReset();
        $this->setParameterToReloadFrame();
    }

    /**
     * Reload upper frame.
     */
    private function setParameterToReloadFrame()
    {
        $this->_aViewData["updatelist"] = "1";
    }
}
