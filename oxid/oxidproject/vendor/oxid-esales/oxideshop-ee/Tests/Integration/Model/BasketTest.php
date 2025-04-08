<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopEnterprise\Tests\Integration\Model;

use OxidEsales\Eshop\Application\Model\Basket;
use OxidEsales\Eshop\Application\Model\BasketReservation;
use OxidEsales\Eshop\Application\Model\User;
use OxidEsales\Eshop\Core\Base;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Session;
use OxidEsales\Eshop\Core\ShopIdCalculator;
use OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Console\SubShopManagerTrait;
use OxidEsales\EshopEnterprise\Tests\Integration\SubShopTrait;
use OxidEsales\TestingLibrary\Services\Library\DatabaseRestorer\DatabaseRestorer;
use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\EshopEnterprise\Application\Model\Article;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class BasketTest
 */
class BasketTest extends UnitTestCase
{

    use SubShopManagerTrait;
    use SubShopTrait;

    private const FIRST_SHOP_PRODUCT_ID = 'testProduct1';
    private const SECOND_SHOP_PRODUCT_ID = 'testProduct2';

    protected function setUp(): void
    {
        $this->databaseRestorer = new DatabaseRestorer();
        $this->databaseRestorer->dumpDB(__CLASS__);

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->databaseRestorer->restoreDB(__CLASS__);

        parent::tearDown();
    }

    public function testSavedBasketIfIsNotDeletedInSubshopsAfterDiscardingReservations(): void
    {
        $this->createProductAndAssignToShop(self::FIRST_SHOP_PRODUCT_ID, 1);
        $this->createProductAndAssignToShop(self::SECOND_SHOP_PRODUCT_ID, 2);

        $this->createSubshopEntry();
        $this->getConfig()->setShopId(2);

        $secondUserId = $this->createActiveUser('testUser2', 'testuser2@oxideshop.dev',2);
        $this->loginUser('testuser2@oxideshop.dev');

        $this->createBasketWithNewProduct(self::SECOND_SHOP_PRODUCT_ID);

        $this->checkSavedBasketInDatabase($secondUserId->getId());

        $this->getConfig()->setShopId(1);

        $secondUserId->logout();
        $fistUserId = $this->createActiveUser('testUser1', 'testuser1@oxideshop.dev',1);
        $this->loginUser('testuser1@oxideshop.dev');

        $this->createBasketWithNewProduct(self::FIRST_SHOP_PRODUCT_ID);

        $this->checkSavedBasketInDatabase($fistUserId->getId());

        $basketReservation = $this->createBasketReservation(self::FIRST_SHOP_PRODUCT_ID);

        $this->assertTrue((bool)DatabaseProvider::getDb()->getOne(
            "select 1 from oxuserbaskets where oxtitle = 'reservations' and  oxuserid = '" . $this->getReservationsId() . "'"
        ));
        $this->assertTrue((bool)DatabaseProvider::getDb()->getOne(
            "select 1 from oxuserbasketitems where oxbasketid = '" . $this->getReservationsId() . "'"
        ));

        $basketReservation->discardUnusedReservations(50);

        $this->assertFalse((bool)DatabaseProvider::getDb()->getOne(
            "select 1 from oxuserbaskets where oxtitle = 'reservations' and  oxuserid = '" . $this->getReservationsId() . "'"
        ));
        $this->assertFalse((bool)DatabaseProvider::getDb()->getOne(
            "select 1 from oxuserbasketitems where oxbasketid = '" . $this->getReservationsId() . "'"
        ));

        $this->assertFalse((bool)DatabaseProvider::getDb()->getOne(
            "select 1 from oxuserbasketitems where oxartid = '" . self::FIRST_SHOP_PRODUCT_ID . "'"
        ));

        $this->checkSavedBasketInDatabase($secondUserId->getId());
    }

    private function createProductAndAssignToShop(string $productId ,int $shopId): void
    {
        $product = $this->createProduct($productId);
        $product->assignToShop($shopId);
    }

    private function createBasketReservation(string $productId): BasketReservation
    {
        $this->setConfigParam('blPerfNoBasketSaving', true);
        $this->setConfigParam('iPsBasketReservationTimeout', 0);
        $this->setConfigParam('blPsBasketReservationEnabled', true);

        $basketReservation = oxNew(BasketReservation::class);
        $basketReservation->getReservations()
            ->setId($this->getReservationsId());
        $basketReservation
            ->getReservations()
            ->addItemToBasket($productId, 1);

        return $basketReservation;
    }

    private function createBasketWithNewProduct(string $productId): void
    {
        $basket = oxNew(Basket::class);
        $basket->addToBasket($productId, 1);
        $basket->calculateBasket(true);
        $this->assertEquals($basket->getItemsCount(), 1);
    }

    /**
     * @param string $userId
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    private function checkSavedBasketInDatabase(string $userId): void
    {
        $this->assertTrue((bool)DatabaseProvider::getDb()->getOne(
            "select 1 from oxuserbaskets where oxtitle = 'savedbasket' and  oxuserid = '" . $userId . "'")
        );

        $this->assertTrue((bool)DatabaseProvider::getDb()->getOne(
            "select 1 from oxuserbasketitems where oxbasketid in (select oxid from oxuserbaskets where 
                        oxuserid in (select oxid from oxuser where oxid='$userId') )"
        ));
    }

    /**
     * @param string $productId
     */
    private function createProduct(string $productId): Article
    {
        $product = oxNew(Article::class);
        $product->setAdminMode(null);
        $product->setId($productId);
        $product->oxarticles__oxprice = new Field(100);
        $product->oxarticles__oxstock = new Field(2);
        $product->oxarticles__oxtitle = new Field($productId);
        $product->save();

        return $product;
    }


    /**
     * @param string $username
     *
     * @return string
     */
    private function loginUser(string $username): string
    {
        $_POST['lgn_usr'] = $username;
        $_POST['lgn_pwd'] = 'asdfasdf';
        $oCmpUser = oxNew('oxcmp_user');

        return $oCmpUser->login();
    }

    /**
     * @param string $userId
     * @param string $username
     * @param int    $shopId
     *
     * @return User
     */
    private function createActiveUser(string $userId, string $username, int $shopId): User
    {
        $addressInfo = [
            'oxfname'     => 'Erna',
            'oxlname'     => 'Hahnentritt',
            'oxstreetnr'  => '117',
            'oxstreet'    => 'Landstrasse',
            'oxzip'       => '22769',
            'oxcity'      => 'Hamburg',
            'oxcountryid' => 'a7c40f631fc920687.20179984',
            'oxcompany'   => 'myCompany',
            'oxfon'       => '217-8918713',
            'oxfax'       => '217-8918713',
            'oxsal'       => 'MRS'
        ];

        $user = oxNew(User::class);
        $user->setId($userId);

        $user->oxuser__oxactive = new Field('1');
        $user->oxuser__oxrights = new Field('user');
        $user->oxuser__oxshopid = new Field($shopId);
        $user->oxuser__oxusername = new Field($username);
        $user->oxuser__oxpassword = new Field(
            'c630e7f6dd47f9ad60ece4492468149bfed3da3429940181464baae99941d0ffa5562' .
            'aaecd01eab71c4d886e5467c5fc4dd24a45819e125501f030f61b624d7d'
        ); //password is asdfasdf
        $user->oxuser__oxpasssalt = new Field('3ddda7c412dbd57325210968cd31ba86');
        $user->oxuser__oxcustnr = new Field('667');
        $user->oxuser__oxcreate = new Field('2015-05-20 22:10:51');
        $user->oxuser__oxregister = new Field('2015-05-20 22:10:51');
        $user->oxuser__oxboni = new Field('1000');

        $user->oxuser__oxfname = new Field($addressInfo['oxfname']);
        $user->oxuser__oxlname = new Field($addressInfo['oxlname']);
        $user->oxuser__oxstreet = new Field($addressInfo['oxstreet']);
        $user->oxuser__oxstreetnr = new Field($addressInfo['oxstreetnr']);
        $user->oxuser__oxcity = new Field($addressInfo['oxcity']);
        $user->oxuser__oxcountryid = new Field($addressInfo['oxcountryid']);
        $user->oxuser__oxzip = new Field($addressInfo['oxzip']);
        $user->oxuser__oxsal = new Field($addressInfo['oxsal']);

        $user->save();

        return $user;
    }


    private function createSubshopEntry()
    {
        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
        $queryBuilderFactory = $this->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder
            ->insert('oxshops')
            ->values(
                [
                    'oxid' => '?',
                ]
            )
            ->setParameter(0, 2)
            ->execute();
        Registry::getConfig()->saveShopConfVar('arr', 'aLanguages', ['de' => 'de', 'en' => 'en'], 2);
        $dataHandler = oxNew(DbMetaDataHandler::class);
        $dataHandler->updateViews();
    }

    private function getReservationsId(): string
    {
        $id = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('basketReservationToken');
        if (!$id) {
            $utilsObject = $this->getUtilsObjectInstance();
            $id = $utilsObject->generateUId();
            \OxidEsales\Eshop\Core\Registry::getSession()->setVariable('basketReservationToken', $id);
        }

        return $id;
    }
}
