<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Model;

use \oxField;

class NewsTest extends \oxUnitTestCase
{
    private $_oNews = null;

    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp(): void
    {
        parent::setUp();
        $oBaseNews = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
        $oBaseNews->init('oxnews');
        $oBaseNews->oxnews__oxshortdesc = new \OxidEsales\Eshop\Core\Field('Test', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBaseNews->oxnews__oxshortdesc_1 = new \OxidEsales\Eshop\Core\Field('Test_news_1', \OxidEsales\Eshop\Core\Field::T_RAW);
        $oBaseNews->Save();

        $this->_oNews = oxNew(\OxidEsales\Eshop\Application\Model\News::class);
        $this->_oNews->load($oBaseNews->getId());
    }

    // 3. trying to delete denied action by RR (EE only)
    public function testAssignDeniedByRR()
    {
        $oTestNews = $this->getMock(\OxidEsales\Eshop\Application\Model\News::class, array('canRead'));
        $oTestNews->expects($this->once())->method('canRead')->will($this->returnValue(false));

        $oTestNews->load($this->_oNews->getId());
        $this->assertFalse($oTestNews->delete());
    }
}
