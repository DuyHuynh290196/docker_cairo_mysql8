<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * Admin order list manager.
 * Performs collection and managing (such as filtering or deleting) function.
 * Admin Menu: Orders -> Display Orders.
 */
class RolesBackendList extends \OxidEsales\Eshop\Application\Controller\Admin\AdminListController
{
    /**
     * Name of chosen object class (default null).
     *
     * @var string
     */
    protected $_sListClass = 'oxrole';

    /**
     * Enable/disable sorting by DESC (SQL) (defaultfalse - disable).
     *
     * @var bool
     */
    protected $_blDesc = false;

    /**
     * Default SQL sorting parameter (default null).
     *
     * @var string
     */
    protected $_sDefSortField = "oxtitle";

    /**
     * Executes parent method parent::render() and returns name of template
     * file "order_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        parent::render();
        if (!$this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            $this->_aViewData['readonly'] = true;
        }

        return "roles_belist.tpl";
    }

    /**
     * deleting related records
     */
    public function deleteEntry()
    {
        if ($this->getConfig()->getConfigParam('blAllowSharedEdit')) {
            // deleting related records
            parent::deleteEntry();
        }
    }

    /**
     * Filtering roles list
     *
     * @return array
     */
    public function buildWhere()
    {
        $this->_aWhere = parent::buildWhere();
        $this->_aWhere["oxroles.oxarea"] = "0";
        $this->_aWhere["oxroles.oxshopid"] = $this->getConfig()->getShopID();

        return $this->_aWhere;
    }
}
