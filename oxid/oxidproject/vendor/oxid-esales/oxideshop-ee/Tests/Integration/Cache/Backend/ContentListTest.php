<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Cache\Backend;

use OxidEsales\Eshop\Application\Model\ContentList;
use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\DatabaseProvider;

class ContentListTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setConfigParam('blCacheActive', true);
        $this->setConfigParam('sDefaultCacheConnector', 'oxFileCacheConnector');
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
        $content->oxcontents__oxcatid= new Field( 'testCategory' );
        $content->save();

        return $content;
    }

    public function testGetCacheKey()
    {
        $content = oxNew(ContentList::class);
        $content->loadServices();
        $this->assertEquals('oxContentList_3_1_de', $content->getCacheKey(3));
    }

    public function testIfContentListIsLoadedFromCached()
    {
        $cache = $this->getCacheBackend();
        $this->createContentObject();

        // cache empty
        $this->assertNull($cache->get('oxContentList_2_1_de'));

        // load
        $contentList = oxNew(ContentList::class);
        $contentList->loadCatMenues();

        $this->assertTrue($contentList->offsetExists('testCategory'));

        // cache filed
        $this->assertNotNull($cache->get('oxContentList_2_1_de'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxcontents` SET `oxtype` = '3' WHERE `oxloadid` = '_testLoadId'");

        //testing if it loaded from cache
        $contentList = oxNew(ContentList::class);
        $contentList->loadCatMenues();
        $this->assertTrue($contentList->offsetExists('testCategory'));
    }

    public function testIfContentListIsLoadedAlwaysFromDbInAdmin()
    {
        $cache = $this->getCacheBackend();
        $this->createContentObject();
        // cache empty
        $this->assertNull($cache->get('oxContentList_2_1_de'));

        // load
        $contentList = oxNew(ContentList::class);
        $contentList->loadCatMenues();

        // cache filed
        $this->assertNotNull($cache->get('oxContentList_2_1_de'));
        $this->assertTrue($contentList->offsetExists('testCategory'));

        // change in directly to db
        DatabaseProvider::getDb()->execute("UPDATE `oxcontents` SET `oxtype` = '3' WHERE `oxloadid` = '_testLoadId'");

        //testing if it loaded from cache
        $contentList = oxNew(ContentList::class);
        $contentList->setAdminMode(true);
        $contentList->loadCatMenues();
        $this->assertFalse($contentList->offsetExists('testCategory'));
    }

    public function testIfContentListIsUpdatedAfterDelete()
    {
        $cache = $this->getCacheBackend();
        $this->createContentObject();
        // cache empty
        $this->assertNull($cache->get('oxContentList_2_1_de'));

        // load
        $contentList = oxNew(ContentList::class);
        $contentList->loadCatMenues();

        // cache filed
        $this->assertNotNull($cache->get('oxContentList_2_1_de'));
        $this->assertTrue($contentList->offsetExists('testCategory'));

        // update
        $content = oxNew(Content::class);
        $content->loadByIdent('_testLoadId');
        $content->delete();

        $this->assertNull($cache->get('oxContentList_2_1_de'));

        //testing if it loaded from cache
        $contentList = oxNew(ContentList::class);
        $contentList->loadCatMenues();
        $this->assertFalse($contentList->offsetExists('testCategory'));
    }

    public function testIfContentListIsUpdatedAfterDeleteInAdmin()
    {
        $cache = $this->getCacheBackend();
        $this->createContentObject();
        // cache empty
        $this->assertNull($cache->get('oxContentList_2_1_de'));

        // load
        $contentList = oxNew(ContentList::class);
        $contentList->loadCatMenues();

        // cache filed
        $this->assertNotNull($cache->get('oxContentList_2_1_de'));
        $this->assertTrue($contentList->offsetExists('testCategory'));

        // update
        $content = oxNew(Content::class);
        $content->setAdminMode(true);
        $content->loadByIdent('_testLoadId');
        $content->delete();

        $this->assertNull($cache->get('oxContentList_2_1_de'));

        //testing if it loaded from cache
        $contentList = oxNew(ContentList::class);
        $contentList->loadCatMenues();
        $this->assertFalse($contentList->offsetExists('testCategory'));
    }
}
