<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Admin order manager.
 * Sets template, that arranges two other templates ("roles_list.tpl"
 * and "roles_main.tpl") to frame.
 * Admin Menu: Users -> Rights and Roles.
 */
class AdminBackEndRoles extends \OxidEsales\Eshop\Application\Controller\Admin\AdminController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'admin_beroles.tpl';
}
