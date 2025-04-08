<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Tests\Unit\Core;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Edition\EditionPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionRootPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionSelector;
use OxidEsales\Eshop\Core\Theme;
use OxidEsales\Eshop\Core\UtilsView;

class UtilsViewTest extends \oxUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $theme = oxNew(Theme::class);
        $theme->load('azure');
        $theme->activate();
    }

    public function testGetEditionTemplateDirs(): void
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $config = $this->getConfig();
        $shopPath = rtrim($config->getConfigParam('sShopDir'), '/') . '/';

        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $dirs = array(
            $professionalPathProvider->getViewsDirectory() . 'azure/tpl/',
            $shopPath . 'Application/views/azure/tpl/',
            $shopPath . 'out/azure/tpl/',
        );

        $utilsView = $this->getMock(UtilsView::class, array('isAdmin'));
        $utilsView->method('isAdmin')->willReturn(false);
        $this->assertArraySubsetOxid($dirs, $utilsView->getTemplateDirs());
    }

    public function testGetEditionTemplateDirsForAdmin(): void
    {
        if ($this->getTestConfig()->getShopEdition() !== 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $config = $this->getConfig();
        $shopPath = rtrim($config->getConfigParam('sShopDir'), '/') . '/';

        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $dirs = array(
            $professionalPathProvider->getViewsDirectory() . 'admin/tpl/',
            $shopPath . 'Application/views/admin/tpl/',
        );

        $utilsView = $this->getMock(UtilsView::class, array('isAdmin'));
        $utilsView->method('isAdmin')->willReturn(true);
        $this->assertArraySubsetOxid($dirs, $utilsView->getTemplateDirs());
    }

    /**
     * oxUtilsView::setTemplateDir() test case
     */
    public function testSetTemplateDir(): void
    {
        if ($this->getTestConfig()->getShopEdition() != 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $config = $this->getConfig();
        $shopPath = rtrim($config->getConfigParam('sShopDir'), '/') . '/';

        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $dirs = array(
            'testDir1',
            'testDir2',
            $professionalPathProvider->getViewsDirectory() . 'azure/tpl/',
            $shopPath . 'Application/views/azure/tpl/',
            $shopPath . 'out/azure/tpl/',
        );

        //
        $utilsView = $this->getMock(UtilsView::class, array("isAdmin"));
        $utilsView->method('isAdmin')->willReturn(false);
        $utilsView->setTemplateDir("testDir1");
        $utilsView->setTemplateDir("testDir2");
        $utilsView->setTemplateDir("testDir1");

        $this->assertArraySubsetOxid($dirs, $utilsView->getTemplateDirs());
    }

    /**
     * Testing smarty config data setter
     */
    // demo mode
    public function testFillCommonSmartyPropertiesANDSmartyCompileCheckDemoShop(): void
    {
        if ($this->getTestConfig()->getShopEdition() != 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $config = oxNew(\OxidEsales\Eshop\Core\Config::class);

        $config->setConfigParam('iDebug', 1);
        $config->setConfigParam('blDemoShop', 1);

        $shopPath = rtrim($config->getConfigParam('sShopDir'), '/') . '/';
        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $templatesDirs = array(
            $professionalPathProvider->getViewsDirectory() . 'azure/tpl/',
            $shopPath . 'Application/views/azure/tpl/',
            $shopPath . 'out/azure/tpl/',
        );

        $vfsStreamWrapper = $this->getVfsStreamWrapper();
        $vfsStreamWrapper->createStructure(array('tmp_directory' => array()));
        $compileDirectory = $vfsStreamWrapper->getRootPath() . 'tmp_directory';
        $config->setConfigParam('sCompileDir', $compileDirectory);

        $smarty = $this->getMock('\Smarty', []);

        $utilsView = oxNew(UtilsView::class);
        $utilsView->setConfig($config);
        $utilsView->UNITfillCommonSmartyProperties($smarty);
        $utilsView->UNITsmartyCompileCheck($smarty);

        $check = array(
            'php_handling'      => 2,
            'security'          => true,
            'php_handling'      => SMARTY_PHP_REMOVE,
            'left_delimiter'    => '[{',
            'right_delimiter'   => '}]',
            'caching'           => false,
            'compile_dir'       => $compileDirectory . "/smarty/",
            'cache_dir'         => $compileDirectory . "/smarty/",
            'template_dir'      => $templatesDirs,
            'debugging'         => true,
            'compile_check'     => true,
        );

        foreach ($check as $varName => $varValue) {
            $this->assertTrue(isset($smarty->$varName));
            if ($varName === 'template_dir') {
                $this->assertArraySubsetOxid($varValue, $smarty->$varName, $varName);
            } else {
                $this->assertEquals($varValue, $smarty->$varName, $varName);
            }
        }
    }

    public function testFillSmartySecuritySettingsForDemoShop(): void
    {
        if ($this->getTestConfig()->getShopEdition() != 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $config = oxNew(Config::class);
        $config->setConfigParam('blDemoShop', 1);

        $smarty = $this->getMock('\Smarty', []);

        $utilsView = oxNew(UtilsView::class);
        $utilsView->setConfig($config);
        $utilsView->UNITfillCommonSmartyProperties($smarty);

        $security_settings = array(
            'PHP_HANDLING'        => false,
            'IF_FUNCS'            =>
                array(
                    0  => 'array',
                    1  => 'list',
                    2  => 'isset',
                    3  => 'empty',
                    4  => 'count',
                    5  => 'sizeof',
                    6  => 'in_array',
                    7  => 'is_array',
                    8  => 'true',
                    9  => 'false',
                    10 => 'null',
                    11 => 'XML_ELEMENT_NODE',
                    12 => 'is_int',
                ),
            'INCLUDE_ANY'         => false,
            'PHP_TAGS'            => false,
            'MODIFIER_FUNCS'      =>
                array(
                    0 => 'count',
                    1 => 'round',
                    2 => 'floor',
                    3 => 'trim',
                    4 => 'implode',
                    5 => 'is_array',
                    6 => 'getimagesize',
                ),
            'ALLOW_CONSTANTS'     => true,
            'ALLOW_SUPER_GLOBALS' => true,
        );

        foreach ($smarty->security_settings as $settingKey => $setting) {
            if (is_array($security_settings[$settingKey])) {
                $this->assertArraySubsetOxid(
                    $security_settings[$settingKey],
                    $setting
                );
            } else {
                $this->assertEquals(
                    $security_settings[$settingKey],
                    $setting
                );
            }
        }
    }

    // non demo mode
    public function testFillCommonSmartyPropertiesANDSmartyCompileCheck(): void
    {
        if ($this->getTestConfig()->getShopEdition() != 'PE') {
            $this->markTestSkipped('This test is for Professional edition only.');
        }

        $config = oxNew(\OxidEsales\Eshop\Core\Config::class);

        $config->setConfigParam('iDebug', 1);
        $config->setConfigParam('blDemoShop', 0);

        $shopPath = rtrim($config->getConfigParam('sShopDir'), '/') . '/';
        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $templatesDirs = array(
            $professionalPathProvider->getViewsDirectory() . 'azure/tpl/',
            $shopPath . 'Application/views/azure/tpl/',
            $shopPath . 'out/azure/tpl/',
        );

        $vfsStreamWrapper = $this->getVfsStreamWrapper();
        $vfsStreamWrapper->createStructure(array('tmp_directory' => array()));
        $compileDirectory = $vfsStreamWrapper->getRootPath() . 'tmp_directory';
        $config->setConfigParam('sCompileDir', $compileDirectory);

        $professionalPathProvider = new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::PROFESSIONAL)));
        $plugins = array(
            $professionalPathProvider->getSmartyPluginsDirectory(),
            $this->getConfigParam('sCoreDir') . 'Smarty/Plugin',
            'plugins'
        );

        $check = array(
            'security'        => false,
            'php_handling'    => (int) $config->getConfigParam('iSmartyPhpHandling'),
            'left_delimiter'  => '[{',
            'right_delimiter' => '}]',
            'caching'         => false,
            'compile_dir'     => $compileDirectory . "/smarty/",
            'cache_dir'       => $compileDirectory . "/smarty/",
            'template_dir'    => $templatesDirs,
            'debugging'       => true,
            'compile_check'   => true,
            'plugins_dir'     => $plugins
        );

        $smarty = $this->getMock('\Smarty', array('register_resource'));
        $smarty->expects($this->once())->method('register_resource');

        $utilsView = oxNew(UtilsView::class);
        $utilsView->setConfig($config);
        $utilsView->UNITfillCommonSmartyProperties($smarty);
        $utilsView->UNITsmartyCompileCheck($smarty);

        foreach ($check as $varName => $varValue) {
            $this->assertTrue(isset($smarty->$varName));
            if ($varName === 'template_dir') {
                $this->assertArraySubsetOxid($varValue, $smarty->$varName, $varName);
            } else {
                $this->assertEquals($varValue, $smarty->$varName, $varName);
            }
        }
    }
}
