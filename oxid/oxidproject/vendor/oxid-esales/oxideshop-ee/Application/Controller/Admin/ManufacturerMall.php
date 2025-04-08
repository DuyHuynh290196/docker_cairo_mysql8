<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Manufacturer mall config class
 */
class ManufacturerMall extends \OxidEsales\Eshop\Application\Controller\Admin\AdminMall
{
    /**
     * DB table used for multiple shops we are going to deal with
     */
    protected $_sMallTable = "oxmanufacturers";

    /**
     * Class name of object to load
     */
    protected $_sObjectClassName = "oxmanufacturer";
}
