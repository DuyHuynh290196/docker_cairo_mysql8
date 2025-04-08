<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Admin voucherserie list manager.
 */
class VoucherSerieMall extends \OxidEsales\Eshop\Application\Controller\Admin\AdminMall
{
    /**
     * DB table used for multiple shops we are going to deal with
     */
    protected $_sMallTable = "oxvoucherseries";

    /**
     * Class name of object to load
     */
    protected $_sObjectClassName = "oxvoucherserie";
}
