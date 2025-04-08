<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Include parent class.
 **/
class AttributeMall extends \OxidEsales\Eshop\Application\Controller\Admin\AdminMall
{
    /**
     * DB table used for multiple shops we are going to deal with.
     */
    protected $_sMallTable = "oxattribute";

    /**
     * Class name of object to load.
     */
    protected $_sObjectClassName = "oxattribute";
}
