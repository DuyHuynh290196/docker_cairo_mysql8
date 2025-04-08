<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxField;

/**
 * @inheritdoc
 */
class UserMain extends \OxidEsales\EshopProfessional\Application\Controller\Admin\UserMain
{
    /**
     * @inheritdoc
     */
    protected function calculateAdditionalRights($userRights)
    {
        $userRights = parent::calculateAdditionalRights($userRights);

        $myConfig = $this->getConfig();

        // performance
        if ($myConfig->isMall()) {
            $editObjectOxid = $this->getEditObjectId();

            // malladmin stuff
            $adminUser = oxNew(\OxidEsales\Eshop\Application\Model\User::class);
            $adminUser->loadAdminUser();
            $isMallAdmin = $adminUser->oxuser__oxrights->value == "malladmin";

            //load all shops
            $shopsList = oxNew(\OxidEsales\Eshop\Core\Model\ListModel::class);
            $shopsList->Init("oxshop");
            $shopsList->selectString("select * from oxshops");

            foreach ($shopsList as $oneShop) {
                if ($isMallAdmin || $adminUser->oxuser__oxrights->value == $oneShop->oxshops__oxid->value || !$this->_allowAdminEdit($editObjectOxid)) {
                    $position = count($userRights);
                    $userRights[$position] = new \stdClass();
                    $userRights[$position]->name = "Admin ( " . $oneShop->oxshops__oxname->value . " )";
                    $userRights[$position]->id = $oneShop->oxshops__oxid->value;
                }
            }
        }

        return $userRights;
    }

    /**
     * @inheritdoc
     */
    protected function onUserCreation($user)
    {
        $user = parent::onUserCreation($user);

        // #1432A.
        $userRights = $user->oxuser__oxrights->value;
        if ($userRights == "user") {
            $shopId = $this->getConfig()->getShopId();
        } elseif ($userRights == "malladmin") {
            $shopId = "1";
        } else {
            $shopId = $userRights;
        }

        $user->oxuser__oxshopid = new \OxidEsales\Eshop\Core\Field($shopId);

        return $user;
    }
}
