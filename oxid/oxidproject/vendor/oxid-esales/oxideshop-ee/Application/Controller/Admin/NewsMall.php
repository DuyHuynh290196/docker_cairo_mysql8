<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Admin Menu: Customer Info -> News -> Mall.
 * @deprecated 6.5.3 "News" feature will be removed completely
 */
class NewsMall extends \OxidEsales\Eshop\Application\Controller\Admin\AdminMall
{
    /**
     * DB table used for multiple shops we are going to deal with.
     */
    protected $_sMallTable = "oxnews";

    /**
     * Class name of object to load.
     */
    protected $_sObjectClassName = "oxnews";
}
