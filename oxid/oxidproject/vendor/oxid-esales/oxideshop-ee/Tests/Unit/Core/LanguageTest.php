<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Theme;

class LanguageTest extends \oxUnitTestCase
{
    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $theme = oxNew(Theme::class);
        $theme->load('azure');
        $theme->activate();
    }

    /**
     * Tear down the fixture.
     *
     * @return null
     */
    protected function tearDown(): void
    {
        $sFileName = getShopBasePath() . "/out/azure/de/my_lang.php";
        if (file_exists($sFileName)) {
            unlink($sFileName);
        }

        parent::tearDown();
    }

    public function testGetMultiLangTables()
    {
        $oLang = oxNew(\OxidEsales\Eshop\Core\Language::class);
        $aTable = $oLang->getMultiLangTables();

        $this->assertTrue(count($aTable) == 23);

        $this->getConfig()->setConfigParam('aMultiLangTables', array('table1', 'table2'));

        $aTable = $oLang->getMultiLangTables();

        $this->assertTrue(count($aTable) == 25);
    }

    public function testGetLangFilesPathContainsSubshopLanguage()
    {
        $sPath = $this->getConfig()->getAppDir();

        $customLangPath = $sPath . "views/azure/1/de/lang.php";

        $oLang = oxNew(\OxidEsales\Eshop\Core\Language::class);
        $this->assertContains($customLangPath, $oLang->UNITgetLangFilesPathArray(0));
    }

    public function testGetLangFilesPathForModules()
    {
        $sFilePath = $this->getConfig()->getConfigParam('sShopDir') . 'modules/oxlangTestModule/translations/de/';

        if (!is_dir($sFilePath)) {
            mkdir($sFilePath, 0755, true);
        }

        file_put_contents($sFilePath . "/test_lang.php", 'langfile');

        $sPath = $this->getConfig()->getAppDir();
        $sShopPath = $this->getConfig()->getConfigParam('sShopDir');
        $aPathArray = array(
            $sPath . "translations/de/lang.php",
            $sPath . "translations/de/translit_lang.php",
            $sPath . "views/azure/de/lang.php",
            $sPath . "views/azure/1/de/lang.php",
            $sShopPath . "modules/oxlangTestModule/translations/de/test_lang.php",
            $sPath . "views/azure/de/cust_lang.php"
        );

        $aInfo = array('oxlangTestModule' => 'oxlangTestModule');

        $oLang = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array("_getActiveModuleInfo"));
        $oLang->expects($this->any())->method('_getActiveModuleInfo')->will($this->returnValue($aInfo));

        $this->assertEquals($aPathArray, $oLang->UNITgetLangFilesPathArray(0));

        unlink($sShopPath . "modules/oxlangTestModule/translations/de/test_lang.php");
        rmdir($sShopPath . "modules/oxlangTestModule/translations/de/");
        rmdir($sShopPath . "modules/oxlangTestModule/translations/");
        rmdir($sShopPath . "modules/oxlangTestModule/");
    }

    public function testGetLangFilesPathForModulesWithApplicationFolder()
    {
        $sFilePath = $this->getConfig()->getConfigParam('sShopDir') . 'modules/oxlangTestModule/Application/translations/de/';

        if (!is_dir($sFilePath)) {
            mkdir($sFilePath, 0755, true);
        }

        file_put_contents($sFilePath . "/test_lang.php", 'langfile');

        $sPath = $this->getConfig()->getAppDir();
        $sShopPath = $this->getConfig()->getConfigParam('sShopDir');
        $aPathArray = array(
            $sPath . "translations/de/lang.php",
            $sPath . "translations/de/translit_lang.php",
            $sPath . "views/azure/de/lang.php",
            $sPath . "views/azure/1/de/lang.php",
            $sShopPath . "modules/oxlangTestModule/Application/translations/de/test_lang.php",
            $sPath . "views/azure/de/cust_lang.php"
        );

        $aInfo = array('oxlangTestModule' => 'oxlangTestModule');

        $oLang = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array("_getActiveModuleInfo"));
        $oLang->expects($this->any())->method('_getActiveModuleInfo')->will($this->returnValue($aInfo));

        $this->assertEquals($aPathArray, $oLang->UNITgetLangFilesPathArray(0));

        unlink($sShopPath . "modules/oxlangTestModule/Application/translations/de/test_lang.php");
        rmdir($sShopPath . "modules/oxlangTestModule/Application/translations/de/");
        rmdir($sShopPath . "modules/oxlangTestModule/Application/translations/");
        rmdir($sShopPath . "modules/oxlangTestModule/Application/");
    }

    // in non amdin mode
    public function testTranslateStringIsNotAdmin()
    {
        $oLang = $this->getMock(\OxidEsales\Eshop\Core\Language::class, array('isAdmin'));
        $oLang->expects($this->any())->method('isAdmin')->will($this->returnValue(false));

        $this->assertEquals('[?] Sie haben Fragen zu diesem Artikel?', $oLang->translateString("QUESTIONS_ABOUT_THIS_PRODUCT_2", 0));
        $this->assertEquals('[?] Have questions about this product?', $oLang->translateString("QUESTIONS_ABOUT_THIS_PRODUCT_2", 1));
    }

    /**
     * Data provider for testGetInvalidViews
     *
     * @return array
     */
    public function providerGetAllShopLanguageIds()
    {
        return array(
            array('aLanguageParams', 'aLanguages', array('lt' => 'Lithuanian', 'de' => 'Deutsch')),
            array('aLanguages', 'aLanguageParams',
                array('de' => array('baseId' => 0,
                    'active' => "1",
                    'sort'   => "1",
                ),
                    'lt' => array('baseId' => 0,
                        'active' => "1",
                        'sort'   => "2",
                    ),
                )),
        );
    }

    /**
     * Tests getting list of invalid views
     *
     * @param string $sLanguageParamNameDisabled - language config parameter that will be disabled
     * @param string $sLanguageParamName         - language config parameter that will be used
     * @param array  $aLanguageParamValue        - language config parameter value
     *
     * @dataProvider providerGetAllShopLanguageIds
     */
    public function testGetAllShopLanguageIds($sLanguageParamNameDisabled, $sLanguageParamName, $aLanguageParamValue)
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $this->_setBaseShopLanguageParameters();

        // disable language config parameter because we are testing each language parameter separately
        $oDb->execute("delete from `oxconfig` WHERE `oxvarname` = '{$sLanguageParamNameDisabled}' ");

        /** @var \oxConfig|\PHPUnit\Framework\MockObject\MockObject $config */
        $config = $this->getMock(\OxidEsales\Eshop\Core\Config::class, array('_loadVarsFromDb'));
        $config->expects($this->any())->method('_loadVarsFromDb')->will($this->returnValue(true));
        $config->setShopId(19);
        $config->saveShopConfVar('aarr', $sLanguageParamName, $aLanguageParamValue);

        $aAssertLanguageIds = array(0 => 'de', 1 => 'ru', 2 => 'lt', 3 => 'en');

        $oLang = oxNew(\OxidEsales\Eshop\Core\Language::class);
        $aAllShopLanguageIds = $oLang->getAllShopLanguageIds();

        $aMissingLanguages = array_diff($aAssertLanguageIds, $aAllShopLanguageIds);

        $this->assertEquals(0, count($aMissingLanguages), "All shop language array is not as expected");
    }

    private function _setBaseShopLanguageParameters() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aLanguages = array(
            'de' => 'Deutch',
            'en' => 'English',
            'ru' => 'Russian'
        );
        $aLanguageParams = array(
            'de' => array('baseId' => 0, 'abbr' => 'de'),
            'ru' => array('baseId' => 1, 'abbr' => 'ru'),
            'en' => array('baseId' => 3, 'abbr' => 'en'),
        );

        $this->getConfig()->saveShopConfVar('aarr', 'aLanguages', $aLanguages);
        $this->getConfig()->saveShopConfVar('aarr', 'aLanguageParams', $aLanguageParams);
        $this->getConfig()->setConfigParam('aLanguages', $aLanguages);
        $this->getConfig()->setConfigParam('aLanguageParams', $aLanguageParams);
    }
}
