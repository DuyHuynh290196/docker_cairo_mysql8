<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Application\Model;

class AttributeTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    /**
     * Initialize the fixture.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->_oAttr = oxNew(\OxidEsales\Eshop\Application\Model\Attribute::class);
        $this->_oAttr->oxattribute__oxtitle = new \oxField("test", \OxidEsales\Eshop\Core\Field::T_RAW);
        $this->_oAttr->save();

        // article attribute
        $oNewGroup = new \oxbase();
        $oNewGroup->Init('oxobject2attribute');
        $oNewGroup->oxobject2attribute__oxobjectid = new \oxField("test_oxid", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oNewGroup->oxobject2attribute__oxattrid = new \oxField($this->_oAttr->getId(), \OxidEsales\Eshop\Core\Field::T_RAW);
        $oNewGroup->oxobject2attribute__oxvalue = new \oxField("testvalue", \OxidEsales\Eshop\Core\Field::T_RAW);
        $oNewGroup->Save();
    }

    /**
     * Test delete attribute denied by RR.
     */
    public function testDeleteDeniedByRR()
    {
        $oAttribute = $this->getMock(\OxidEsales\Eshop\Application\Model\Attribute::class, array('canDelete'));
        $oAttribute->expects($this->once())->method('canDelete')->will($this->returnValue(false));

        $oAttribute->load($this->_oAttr->getId());
        $this->assertFalse($oAttribute->delete());
    }

    /**
     * Test get attribute id.
     */
    public function testGetArticleIds()
    {
        $articles = $this->_oAttr->getArticleIds();
        $this->assertEquals(1, count($articles));
        $this->assertEquals('test_oxid', $articles[0]);
    }
}
