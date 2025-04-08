<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class PaymentMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\PaymentMain
{
    /**
     * @inheritdoc
     */
    public function save()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }
        parent::save();
    }

    /**
     * @inheritdoc
     */
    public function saveinnlang()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }
        parent::saveinnlang();
    }

    /**
     * @inheritdoc
     */
    public function delFields()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }
        parent::delFields();
    }

    /**
     * @inheritdoc
     */
    public function addField()
    {
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            return;
        }
        parent::addField();
    }
}
