<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Model;

use oxDb;

/**
 * @inheritdoc
 */
class NewsSubscribed extends \OxidEsales\EshopProfessional\Application\Model\NewsSubscribed
{
    /**
     * Is mall user
     *
     * @var bool
     */
    protected $_blMallUsers = false;

    /**
     * Class constructor, initiates parent constructor (parent::oxBase()).
     */
    public function __construct()
    {
        $this->setMallUsers($this->getConfig()->getConfigParam('blMallUsers'));
        parent::__construct();
    }

    /**
     * Mall user status setter
     *
     * @param bool $blMallUsers Is mall user
     */
    public function setMallUsers($blMallUsers)
    {
        $this->_blMallUsers = $blMallUsers;
    }

    /**
     * @inheritdoc
     */
    protected function getSubscribedUserIdByEmail($email)
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $params = [
            ':oxemail' => (string) $email,
            ':oxshopid' => \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId()
        ];

        if ($this->_blMallUsers) {
            $userOxid = $database->getOne("select oxid from oxnewssubscribed where oxemail = :oxemail and oxshopid = :oxshopid", $params);
        } else {
            $userOxid = $database->getOne("select oxnewssubscribed.oxid from oxnewssubscribed left join oxuser on oxnewssubscribed.oxuserid=oxuser.oxid where oxnewssubscribed.oxemail = :oxemail and oxuser.oxshopid = :oxshopid and oxnewssubscribed.oxshopid = :oxshopid", $params);
        }

        return $userOxid;
    }
}
