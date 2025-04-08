<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\StandardException;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;

/**
 * @inheritdoc
 */
class User extends \OxidEsales\EshopProfessional\Application\Model\User
{
    /**
     * Checks if object can be read.
     *
     * @return bool
     */
    public function canRead()
    {
        return true;
    }

    /**
     * Checks if object field can be read/viewed by user.
     *
     * @param string $field name of field to check
     *
     * @return bool
     */
    public function canReadField($field)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function onChangeUserData($invAddress)
    {
        try {
            $inputValidator = Registry::getInputValidator();
            $inputValidator->checkVatId($this, $invAddress);
            $this->oxuser__oxustidstatus = new Field('1');
        } catch (StandardException $e) {
            $this->oxuser__oxustidstatus = new Field('0');
        }
    }

    /**
     * @inheritdoc
     */
    protected function formQueryPartForAdminView($shopID, $isAdmin)
    {
        $shopSelectQuery = parent::formQueryPartForAdminView($shopID, $isAdmin);
        if (empty($shopSelectQuery) && !$this->_blMallUsers) {
            $shopSelectQuery = " and ( oxshopid = '{$shopID}' or oxrights = 'malladmin' or oxrights = '{$shopID}' ) ";
        }

        return $shopSelectQuery;
    }

    /**
     * Removes from rights and roles.
     *
     * @param string $quotedObjectId
     */
    protected function deleteAdditionally($quotedObjectId)
    {
        DatabaseProvider::getDb()->execute("delete from oxobject2role where oxobjectid = {$quotedObjectId}");
    }

    /**
     * @inheritdoc
     */
    protected function updateGetOrdersQuery($query)
    {
        $query = parent::updateGetOrdersQuery($query);
        //#1546 - Shop id check added, if it is not multishop.
        $config = Registry::getConfig();
        if (!$config->isMultiShop()) {
            $query .= ' and oxshopid = "' . $config->getShopId() . '" ';
        }

        return $query;
    }

    /**
     * Makes LDAP login.
     *
     * @param string $userName
     * @param string $password
     *
     * @throws \oxUserException
     */
    protected function onLogin($userName, $password)
    {
        parent::onLogin($userName, $password);

        $config = Registry::getConfig();
        $isLoginToAdminBackend = $this->isAdmin();
        /** Staging mode log in */
        if (
            !$this->isLoaded() &&
            $config->isStagingMode() &&
            $isLoginToAdminBackend &&
            $password === 'admin' && $userName === 'admin'
        ) {
            $userId = DatabaseProvider::getDb()->getOne("select `oxid` from oxuser where oxrights = 'malladmin' ORDER BY OXCUSTNR");
            if ($userId) {
                $this->load($userId);
            }
        }

        /** LDAP log in */
        if (
            !$this->isLoaded() &&
            $config->getConfigParam('blUseLDAP')
        ) {
            $shopId = $config->getShopId();
            $this->_ldapLogin($userName, $password, $shopId, $this->_getShopSelect($config, $shopId, $isAdmin));
        }
    }

    /**
     * @inheritdoc
     */
    protected function formUserCookieQuery($user, $shopId)
    {
        $query = parent::formUserCookieQuery($user, $shopId);
        if ($this->_blMallUsers) {
            $query .= " and ( oxshopid = '$shopId' or oxrights = 'malladmin' or oxrights = '$shopId') ";
        }

        return $query;
    }

    /**
     * return user id by user name
     *
     * @param string $userName
     *
     * @return false|string
     */
    public function getIdByUserName($userName)
    {
        if ($this->_blMallUsers || $this->isAdmin()) {
            $userId = DatabaseProvider::getDb()
                ->getOne(
                    'SELECT `OXID` FROM `oxuser` WHERE `OXUSERNAME` = :OXUSERNAME',
                    [
                        ':OXUSERNAME' => $userName
                    ]
                );
        } else {
            $userId = parent::getIdByUserName($userName);
        }

        return $userId;
    }
}
