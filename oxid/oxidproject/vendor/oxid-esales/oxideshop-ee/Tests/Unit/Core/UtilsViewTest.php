<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Theme;
use OxidEsales\Eshop\Core\Edition\EditionPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionRootPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionSelector;

class UtilsViewTest extends \oxUnitTestCase
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
     * Initialize the fixture.
     *
     * @return null
     */

    public function testFillCommonSmartyPropertiesANDSmartyCompileCheckDemoShop()
    {
        $config = oxNew(\OxidEsales\Eshop\Core\Config::class);

        $config->setConfigParam('iDebug', 1);
        $config->setConfigParam('blDemoShop', 1);

        $templatesDirectories = [];

        $tplDir = $config->getTemplateDir($config->isAdmin());
        if ($tplDir) {
            $templatesDirectories[] = $tplDir;
        }

        $tplDir = $config->getOutDir() . $config->getConfigParam('sTheme') . "/tpl/";
        if ($tplDir && !in_array($tplDir, $templatesDirectories)) {
            $templatesDirectories[] = $tplDir;
        }

        $config->setConfigParam('sCompileDir', $this->getCompileDirectory());

        $smarty = $this->getMock('\Smarty', ['register_resource', 'register_prefilter']);
        $smarty->expects($this->once())->method('register_resource')
            ->with(
                $this->equalTo('ox'),
                $this->equalTo(
                    [
                        'ox_get_template',
                        'ox_get_timestamp',
                        'ox_get_secure',
                        'ox_get_trusted',
                    ]
                )
            );
        $smarty->expects($this->once())->method('register_prefilter')
            ->with($this->equalTo('smarty_prefilter_oxblock'));

        $utilsView = oxNew(\OxidEsales\Eshop\Core\UtilsView::class);
        $utilsView->setConfig($config);
        $utilsView->UNITfillCommonSmartyProperties($smarty);
        $utilsView->UNITsmartyCompileCheck($smarty);

        $smarty = new \smarty();
        $mockedConfig = $this->getMock(\OxidEsales\Eshop\Core\Config::class, ['isProductiveMode']);
        $mockedConfig->expects($this->once())->method('isProductiveMode')->will($this->returnValue(true));
        $utilsView = oxNew(\OxidEsales\Eshop\Core\UtilsView::class);
        $utilsView->setConfig($mockedConfig);
        $utilsView->UNITsmartyCompileCheck($smarty);
        $this->assertFalse($smarty->compile_check);
    }

    public function testGetEditionTemplateDirsContainsAzure()
    {
        $dirs = $this->getDirectoryStructureForAzure();
        $utilsView = $this->getUtilsViewMockNotAdmin();

        $this->assertArraySubsetOxid($dirs, $utilsView->getTemplateDirs());
    }

    public function testGetEditionTemplateDirsOnlyAzure()
    {
        $dirs = $this->getDirectoryStructureForAzure();
        $utilsView = $this->getUtilsViewMockNotAdmin();

        $this->assertEquals($dirs, $utilsView->getTemplateDirs());
    }

    public function testGetEditionTemplateDirsForAdminContainsAzure()
    {
        $dirs = $this->getDirectoryStructureForAdmin();
        $utilsView = $this->getUtilsViewMockBeAdmin();

        $this->assertArraySubsetOxid($dirs, $utilsView->getTemplateDirs());
    }

    public function testGetEditionTemplateDirsForAdminOnlyAzure()
    {
        $dirs = $this->getDirectoryStructureForAdmin();
        $utilsView = $this->getUtilsViewMockBeAdmin();

        $this->assertEquals($dirs, $utilsView->getTemplateDirs());
    }

    public function testSetTemplateDirContainsAzure()
    {
        $dirs = $this->getDirectoryStructureForAzure();
        array_unshift($dirs, 'testDir1', 'testDir2');

        $utilsView = $this->getUtilsViewMockNotAdmin();
        $utilsView->setTemplateDir("testDir1");
        $utilsView->setTemplateDir("testDir2");
        $utilsView->setTemplateDir("testDir1");

        $this->assertArraySubsetOxid($dirs, $utilsView->getTemplateDirs());
    }

    public function testSetTemplateDirOnlyAzure()
    {
        $dirs = $this->getDirectoryStructureForAzure();
        array_unshift($dirs, 'testDir1', 'testDir2');

        $utilsView = $this->getUtilsViewMockNotAdmin();
        $utilsView->setTemplateDir("testDir1");
        $utilsView->setTemplateDir("testDir2");
        $utilsView->setTemplateDir("testDir1");

        $this->assertEquals($dirs, $utilsView->getTemplateDirs());
    }

    /**
     * @group smartyplugin
     */
    public function testFillCommonSmartyPropertiesANDSmartyCompileCheckContainsAzure()
    {
        $config = oxNew(\OxidEsales\Eshop\Core\Config::class);

        $config->setConfigParam('iDebug', 1);
        $config->setConfigParam('blDemoShop', 0);

        $enterprisePathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::ENTERPRISE)));
        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $templatesDirs = $this->getDirectoryStructureForAzure();

        $compileDirectory = $this->getCompileDirectory();
        $config->setConfigParam('sCompileDir', $compileDirectory);

        $plugins = [
            $enterprisePathProvider->getSmartyPluginsDirectory(),
            $professionalPathProvider->getSmartyPluginsDirectory(),
            $this->getConfigParam('sCoreDir') . 'Smarty/Plugin',
            'plugins'
        ];

        $check = $this->getSmartyCheckArray($config, $compileDirectory, $plugins);

        $smarty = $this->getMock('\Smarty', ['register_resource']);
        $smarty->expects($this->once())->method('register_resource');

        $utilsView = oxNew(\OxidEsales\Eshop\Core\UtilsView::class);
        $utilsView->setConfig($config);
        $utilsView->UNITfillCommonSmartyProperties($smarty);
        $utilsView->UNITsmartyCompileCheck($smarty);

        foreach ($check as $varName => $varValue) {
            $this->assertTrue(isset($smarty->$varName));
            $this->assertEquals($varValue, $smarty->$varName, $varName);
        }

        $this->assertArraySubsetOxid($templatesDirs, $smarty->template_dir);
    }

    /**
     * @group smartyplugin
     */
    public function testFillCommonSmartyPropertiesANDSmartyCompileCheckOnlyAzure()
    {
        $config = oxNew(\OxidEsales\Eshop\Core\Config::class);

        $config->setConfigParam('iDebug', 1);
        $config->setConfigParam('blDemoShop', 0);

        $enterprisePathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::ENTERPRISE)));
        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $templatesDirs = $this->getDirectoryStructureForAzure();

        $compileDirectory = $this->getCompileDirectory();
        $config->setConfigParam('sCompileDir', $compileDirectory);

        $plugins = [
            $enterprisePathProvider->getSmartyPluginsDirectory(),
            $professionalPathProvider->getSmartyPluginsDirectory(),
            $this->getConfigParam('sCoreDir') . 'Smarty/Plugin',
            'plugins'
        ];

        $check = $this->getSmartyCheckArray($config, $compileDirectory, $plugins);
        $check['template_dir'] = $templatesDirs;

        $smarty = $this->getMock('\Smarty', ['register_resource']);
        $smarty->expects($this->once())->method('register_resource');

        $utilsView = oxNew(\OxidEsales\Eshop\Core\UtilsView::class);
        $utilsView->setConfig($config);
        $utilsView->UNITfillCommonSmartyProperties($smarty);
        $utilsView->UNITsmartyCompileCheck($smarty);

        foreach ($check as $varName => $varValue) {
            $this->assertTrue(isset($smarty->$varName));
            $this->assertEquals($varValue, $smarty->$varName, $varName);
        }
    }

    /**
     * @return string
     */
    private function getShopPath()
    {
        $config = $this->getConfig();
        $shopPath = rtrim($config->getConfigParam('sShopDir'), '/') . '/';
        return $shopPath;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getUtilsViewMockNotAdmin()
    {
        $utilsView = $this->getMock(\OxidEsales\Eshop\Core\UtilsView::class, ['isAdmin']);
        $utilsView->expects($this->any())->method('isAdmin')->will($this->returnValue(false));
        return $utilsView;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getUtilsViewMockBeAdmin()
    {
        $utilsView = $this->getMock(\OxidEsales\Eshop\Core\UtilsView::class, ['isAdmin']);
        $utilsView->expects($this->any())->method('isAdmin')->will($this->returnValue(true));
        return $utilsView;
    }

    /**
     * @param $config
     * @param $compileDirectory
     * @param $plugins
     * @return array
     */
    private function getSmartyCheckArray($config, $compileDirectory, $plugins)
    {
        $check = [
            'security' => false,
            'php_handling' => (int)$config->getConfigParam('iSmartyPhpHandling'),
            'left_delimiter' => '[{',
            'right_delimiter' => '}]',
            'caching' => false,
            'compile_dir' => $compileDirectory . "/smarty/",
            'cache_dir' => $compileDirectory . "/smarty/",
            'debugging' => true,
            'compile_check' => true,
            'plugins_dir' => $plugins
        ];
        return $check;
    }

    /**
     * @return array
     * @internal param $shopPath
     */
    private function getDirectoryStructureForAzure()
    {
        $shopPath = $this->getShopPath();
        $enterprisePathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::ENTERPRISE)));
        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $dirs = [
            $enterprisePathProvider->getViewsDirectory() . 'azure/tpl/',
            $professionalPathProvider->getViewsDirectory() . 'azure/tpl/',
            $shopPath . 'Application/views/azure/tpl/',
            $shopPath . 'out/azure/tpl/',
        ];
        return $dirs;
    }

    /**
     * @return array
     * @internal param $shopPath
     */
    private function getDirectoryStructureForAdmin()
    {
        $shopPath = $this->getShopPath();
        $enterprisePathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::ENTERPRISE)));
        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $dirs = [
            $enterprisePathProvider->getViewsDirectory() . 'admin/tpl/',
            $professionalPathProvider->getViewsDirectory() . 'admin/tpl/',
            $shopPath . 'Application/views/admin/tpl/',
        ];
        return $dirs;
    }

    /**
     * @return string
     */
    private function getCompileDirectory()
    {
        $vfsStreamWrapper = $this->getVfsStreamWrapper();
        $vfsStreamWrapper->createStructure(['tmp_directory' => []]);
        $compileDirectory = $vfsStreamWrapper->getRootPath() . 'tmp_directory';
        return $compileDirectory;
    }
}
