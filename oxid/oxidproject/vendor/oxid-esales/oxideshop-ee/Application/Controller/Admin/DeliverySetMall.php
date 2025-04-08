<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Admin deliveryset payment manager.
 * Admin Menu: Shop settings -> Shipping & Handling Set -> Mall
 */
class DeliverySetMall extends AdminMall
{
    /**
     * DB table used for multiple shops we are going to deal with
     */
    protected $_sMallTable = "oxdeliveryset";

    /**
     * Class name of object to load
     */
    protected $_sObjectClassName = "oxdeliveryset";
}
