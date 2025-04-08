<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version   OXID eSales Visual CMS
 */

namespace OxidEsales\VisualCmsModule\Tests\Integration\Controller;

use OxidEsales\VisualCmsModule\Application\Controller\PreviewController;

use OxidEsales\TestingLibrary\UnitTestCase;

/**
 * Class ddVisualEditorPreviewTest
 */
class PreviewControllerTest extends UnitTestCase
{

    /**
     * Test render return when passing various active themes.
     */
    public function testRenderContentObjectForPreview()
    {
        //content data
        $contentObjectData = array(
            'oxcontents__oxtitle'   => 'AGB',
            'oxcontents__oxcontent' => 'Some text'
        );

        //set request parameter
        $this->setRequestParameter('id', 'oxagb');

        //set session parameter
        $this->setSessionParam('ddpreviewcontent_oxagb', $contentObjectData);

        $content = oxNew(PreviewController::class );
        $this->assertEquals('ddoe_roxive_content.tpl', $content->render());

        /** @var oxcontent $contentObject */
        $contentObject = $content->getContent();
        $this->assertInstanceOf('oxcontent', $contentObject);
        $this->assertEquals('oxagb', $contentObject->getId());
        $this->assertEquals('AGB', $contentObject->oxcontents__oxtitle->value);
        $this->assertEquals('Some text', $contentObject->oxcontents__oxcontent->value);
        $this->assertEquals(1, $contentObject->oxcontents__oxactive->value);
        $this->assertEquals('ddpreview', $contentObject->oxcontents__oxloadid->value);
    }

}
