<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Admin article main delivery manager.
 * Admin Menu: Shop settings -> Shipping & Handling -> Mall.
 */
class DeliveryMall extends \OxidEsales\Eshop\Application\Controller\Admin\AdminMall
{
    /**
     * DB table used for multiple shops we are going to deal with
     */
    protected $_sMallTable = "oxdelivery";

    /**
     * Class name of object to load
     */
    protected $_sObjectClassName = "oxdelivery";
}
