<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Model;

use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 *
 * Content caching in cache backend cases:
 *
 * - key generation: base is loadid, not oxid;
 * - select from cache;
 * - update: cache invalidation;
 * - delete: cache by ident should be empty;
 *
 */
class ContentTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setConfigParam( 'blCacheActive', true );
        $this->setConfigParam( 'sDefaultCacheConnector', 'oxFileCacheConnector' );
    }

    protected function tearDown(): void
    {
        $this->cleanUpTable('oxcontents');
        $cache = $this->getCacheBackend();
        $cache->flush();

        parent::tearDown();
    }

    /**
     * @return Content
     */
    private function createContentObject(): Content
    {
        $content = oxNew(Content::class);
        $content->setId('_testContent');
        $content->oxcontents__oxloadid = new Field( '_testLoadId' );
        $content->oxcontents__oxtype = new Field( 2 );
        $content->oxcontents__oxtitle = new Field( 'testContentCategory' );
        $content->oxcontents__oxactive = new Field( 1 );
        $content->oxcontents__oxsnippet= new Field( 0 );
        $content->save();

        return $content;
    }

    public function testGetCacheKey()
    {
        $content = $this->createContentObject();

        $this->assertEquals('oxContent__testLoadId_1_de', $content->getCacheKey('_testLoadId'));
        $this->assertEquals('oxContent__testLoadId_1_de', $content->getCacheKey());

        //Setting different load id
        $content->oxcontents__oxloadid = new Field( '_testLoadIdDifferent' );
        $this->assertEquals('oxContent__testLoadIdDifferent_1_de', $content->getCacheKey('_testLoadIdDifferent'));

        // must get from db, if load id not passed
        $this->assertEquals('oxContent__testLoadId_1_de', $content->getCacheKey());
    }

    public function testLoadCorrectDataAfterUpdate()
    {
        $cache = $this->getCacheBackend();
        $content = $this->createContentObject();

        // cache empty
        $this->assertNull($cache->get( 'oxContent__testLoadId_1_de' ));

        // load
        $content->loadByIdent('_testLoadId');

        // cache filed
        $this->assertNotNull($cache->get( 'oxContent__testLoadId_1_de' ));

        // update
        $content->oxcontents__oxtitle = new Field( 'testContentCategoryUpdated' );
        $content->save();

        // cache empty
        $this->assertNull( $cache->get( 'oxContent__testLoadId_1_de' ) );

        //testing update
        $content = oxNew(Content::class);
        $content->loadByIdent('_testLoadId');
        $this->assertEquals( 'testContentCategoryUpdated', $content->oxcontents__oxtitle->value );
    }

    public function testLoadCorrectDataAfterUpdateInAdmin()
    {
        $cache = $this->getCacheBackend();
        $content = $this->createContentObject();

        // cache empty
        $this->assertNull($cache->get('oxContent__testLoadId_1_de'));

        // load
        $content->loadByIdent('_testLoadId');

        // cache filed
        $this->assertNotNull($cache->get('oxContent__testLoadId_1_de'));

        // update
        $content->setAdminMode(true);
        $content->oxcontents__oxtitle = new Field('testContentCategoryUpdated');
        $content->save();

        // cache empty
        $this->assertNull($cache->get('oxContent__testLoadId_1_de'));

        //testing update
        $content = oxNew(Content::class);
        $content->loadByIdent('_testLoadId');
        $this->assertEquals('testContentCategoryUpdated', $content->oxcontents__oxtitle->value);
    }

    public function testLoadCorrectDataAfterDelete()
    {
        $cache = $this->getCacheBackend();
        $content = $this->createContentObject();

        // cache empty
        $this->assertNull($cache->get( 'oxContent__testLoadId_1_de' ));

        // load
        $content->loadByIdent('_testLoadId');

        // cache filed
        $this->assertNotNull($cache->get( 'oxContent__testLoadId_1_de' ));

        // delete
        $content->delete();

        // cache empty
        $this->assertNull( $cache->get( 'oxContent__testLoadId_1_de' ) );

        //testing if it is deleted
        $content = oxNew(Content::class);
        $this->assertFalse( $content->loadByIdent('_testLoadId') );
    }

    public function testContentLoadFromCache()
    {
        $cache = $this->getCacheBackend();
        $content = $this->createContentObject();

        // cache empty
        $this->assertNull($cache->get('oxContent__testLoadId_1_de'));

        // load
        $content->loadByIdent('_testLoadId');
        // cache filed
        $this->assertNotNull($cache->get( 'oxContent__testLoadId_1_de' ));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxcontents` SET `oxtitle` = 'testContentCategoryUpdated' WHERE `oxloadid` = '_testLoadId'");

        //testing if it loaded from cache
        $content = oxNew(Content::class);
        $content->loadByIdent('_testLoadId');
        $this->assertEquals( 'testContentCategory', $content->oxcontents__oxtitle->value );
    }

    public function testContentLoadAlwaysFromDbInAdmin()
    {
        $cache = $this->getCacheBackend();
        $content = $this->createContentObject();

        // cache empty
        $this->assertNull($cache->get('oxContent__testLoadId_1_de'));

        // load
        $content->loadByIdent('_testLoadId');
        // cache filed
        $this->assertNotNull($cache->get('oxContent__testLoadId_1_de'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxcontents` SET `oxtitle` = 'testContentCategoryUpdated' WHERE `oxloadid` = '_testLoadId'");

        //testing if it loaded from cache
        $content = oxNew(Content::class);
        $content->setAdminMode(true);
        $content->loadByIdent('_testLoadId');
        $this->assertEquals('testContentCategoryUpdated', $content->oxcontents__oxtitle->value);
    }
}
