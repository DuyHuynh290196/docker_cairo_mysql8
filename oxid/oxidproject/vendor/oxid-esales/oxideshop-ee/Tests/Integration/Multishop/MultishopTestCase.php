<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Multishop;

use OxidEsales\TestingLibrary\UnitTestCase;
use RecursiveRegexIterator;
use RegexIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use oxDb;
use OxidEsales\Eshop\Core\Registry;

abstract class MultishopTestCase extends UnitTestCase
{
    /**
     * @var string
     */
    protected $testDir = __DIR__;

    /**
     * Test case directory array
     *
     * @var array
     */
    protected $testCaseDir = array(
        'DataProvilers',
    );

    /**
     * @var array
     */
    protected $fixtureTemplate = array(
        'shops' => array(),
    );

    /**
     * List of test shops.
     *
     * @var array
     */
    protected $shops = array();

    /**
     * List of test articles.
     *
     * @var array
     */
    protected $articles = array();

    /**
     * List of test categories.
     *
     * @var array
     */
    protected $categories = array();

    /**
     * List of product to category assignments.
     *
     * @var array
     */
    protected $productCategoryRelations = array();

    /**
     * Creates shop for test.
     *
     * @param array $data Shop data.
     *
     * @return \OxidEsales\Eshop\Application\Model\Shop
     */
    protected function _createShop($data) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $params = array();
        foreach ($data as $key => $value) {
            $field = "oxshops__{$key}";
            $params[$field] = $value;
        }

        $shop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
        $shop->assign($params);
        $shop->save();

        //copy main configuration options
        $this->_createConfigOptions($shop);

        if ($shop->oxshops__oxisinherited->value) {
            $this->_setShopInheritance($shop, true);
        }


        $this->shops[$shop->getId()] = $shop;

        return $shop;
    }

    /**
     * Copy config options from base shop to current shop
     *
     * @param $shop
     */
    protected function _createConfigOptions($shop) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $config = Registry::getConfig();
        $utilsObject = Registry::getUtilsObject();

        $copyVars = array(
            "aLanguages"
        );

        $copyVarsPrepared = '';
        if (count($copyVars)) {
            $copyVarsPrepared = " and oxvarname in ( '" . join("', '", $copyVars) . "')";
        }

        $select = "select oxvarname, oxvartype, DECODE( oxvarvalue, " . $database->quote($config->getConfigParam('sConfigKey')) . ") as oxvarvalue, oxmodule from oxconfig where oxshopid = '1' $copyVarsPrepared ";
        $rs = $database->select($select);
        if ($rs != false && $rs->count() > 0) {
            while (!$rs->EOF) {
                $id = $utilsObject->generateUID();
                $insertSql = "insert into oxconfig (oxid, oxshopid, oxvarname, oxvartype, oxvarvalue, oxmodule) values ( '$id', " . $database->quote($shop->getId())
                           . ", " . $database->quote($rs->fields[0])
                           . ", " . $database->quote($rs->fields[1])
                           . ",  ENCODE( " . $database->quote($rs->fields[2])
                           . ", '" . $config->getConfigParam('sConfigKey')
                           . "')"
                           . ", " . $database->quote($rs->fields[3]) . " )";
                $database->execute($insertSql);
                $rs->fetchRow();
            }
        }
    }

    /**
     * Sets "Inherit All" option for subshop
     *
     * @param \OxidEsales\Eshop\Application\Model\Shop $shop        Shop object
     * @param bool   $inheritAll "Inherit All" value
     */
    protected function _setShopInheritance($shop, $inheritAll) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $config = Registry::getConfig();
        $inheritAll = $inheritAll ? "true" : "false";
        $multiShopTables = $config->getConfigParam('aMultiShopTables');
        foreach ($multiShopTables as $oneMultishopTable) {
            $config->saveShopConfVar("bool", 'blMallInherit_' . strtolower($oneMultishopTable), $inheritAll, $shop->oxshops__oxid->value);
        }
    }

    /**
     * Deletes shops for test.
     */
    protected function _deleteShops() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        /** @var \OxidEsales\Eshop\Application\Model\Shop $shop */
        foreach ($this->shops as $shop) {
            $shop->delete();

            //remove config options
            $query = "delete from oxconfig where oxshopid = " . $shop->getId() . " ";
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute($query);
        }
    }

    /**
     * Creates article for test.
     *
     * @param array $data Article data.
     *
     * @return \OxidEsales\Eshop\Application\Model\Article
     */
    protected function _createArticle($data) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $params = array();
        $shopIds = array();
        foreach ($data as $key => $value) {
            if ($key == "oxinheritedshopids") {
                $shopIds = $value;
            } else {
                $field = "oxarticles__{$key}";
                $params[$field] = $value;
            }
        }

        if (isset($params['oxarticles__oxshopid']) && !empty($params['oxarticles__oxshopid'])) {
            $this->getConfig()->setShopId($params['oxarticles__oxshopid']);
        } else {
            $this->getConfig()->setShopId(1);
        }

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $article->assign($params);
        $article->save();

        if (!empty($shopIds)) {
            foreach ($shopIds as $shopId) {
                $article->assignToShop($shopId);
            }
        }

        $this->articles[$article->getId()] = $article;

        return $article;
    }

    /**
     * Creates category for test.
     *
     * @param array $data Input data.
     *
     * @return \OxidEsales\Eshop\Application\Model\Category
     */
    protected function _createCategory($data) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $params = array();
        foreach ($data as $key => $value) {
            $field = "oxcategories__{$key}";
            $params[$field] = $value;
        }

        if (isset($params['oxcategories__oxshopid']) && !empty($params['oxcategories__oxshopid'])) {
            $this->getConfig()->setShopId($params['oxcategories__oxshopid']);
        } else {
            $this->getConfig()->setShopId(1);
        }

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->assign($params);
        $category->save();

        $this->categories[$category->getId()] = $category;

        return $category;
    }

    /**
     * Creates object 2 category relation  for test.
     *
     * @param array $data Input data.
     *
     * @return oxObject2Category
     */
    protected function _createObject2Category($data) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $params = array();
        foreach ($data as $key => $value) {
            $field = "oxobject2category__{$key}";
            $params[$field] = $value;
        }

        $object2Category = oxNew(\OxidEsales\Eshop\Application\Model\Object2Category::class);
        $object2Category->assign($params);
        $object2Category->save();

        $this->productCategoryRelations[$object2Category->getId()] = $object2Category;

        return $object2Category;
    }

    /**
     * Deletes articles for test.
     */
    protected function _deleteArticles() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        /** @var \OxidEsales\Eshop\Application\Model\Article $article */
        foreach ($this->articles as $article) {
            $article->delete();
        }
    }

    /**
     * Deletes categories for test.
     */
    protected function _deleteCategories() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        /** @var \OxidEsales\Eshop\Application\Model\Category $category */
        foreach ($this->categories as $category) {
            $category->delete();
        }
    }

    /**
     * Deletes for test.
     */
    protected function _deleteO2C() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        foreach ($this->productCategoryRelations as $object2Category) {
            $object2Category->delete();
        }
    }

    /**
     * Deletes created test date
     */
    protected function _deleteFixture() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $this->_deleteO2C();
        $this->_deleteCategories();
        $this->_deleteArticles();
        $this->_deleteShops();
    }

    /**
     * Gets article created in test.
     *
     * @param string $articleId Article ID.
     *
     * @return \OxidEsales\Eshop\Application\Model\Article
     */
    protected function _getArticleById($articleId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->articles[$articleId];
    }

    /**
     * Gets shop created in test.
     *
     * @param string $shopId Shop ID.
     *
     * @return \OxidEsales\Eshop\Application\Model\Shop
     */
    protected function _getShopById($shopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->shops[$shopId];
    }

    /**
     * Updates test data with missing template information.
     *
     * @param array $data Test case data.
     *
     * @return array
     */
    protected function _updateTemplate($data) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        foreach ($this->fixtureTemplate as $item => $itemTemplate) {
            if (!isset($data[$item])) {
                $data[$item] = $itemTemplate;
            } else {
                foreach ($itemTemplate as $subItem => $subItemTemplate) {
                    if (!isset($data[$item][$subItem])) {
                        $data[$item][$subItem] = $subItemTemplate;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Sets up the fixture.
     *
     * @param array $testCase Test cases with expected results.
     */
    protected function _setupFixture($testCase) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        foreach ($testCase['shops'] as $data) {
            $this->_createShop($data);
        }
    }

    /**
     * Generates fixtures and gets test cases.
     *
     * @param array $directories Test case directory array
     *
     * @return array
     */
    protected function _getTestCases($directories) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $global = array();

        foreach ($directories as $directory) {
            $path = "{$this->testDir}/$directory/";

            $regexIterator = new RegexIterator(
                new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($path)
                ),
                '/^.+\.php$/i',
                RecursiveRegexIterator::GET_MATCH
            );

            foreach ($regexIterator as $files) {
                $filename = $files[0];

                $data = include $filename;
                if ($data) {
                    $global[$filename] = array($this->_updateTemplate($data));
                }
            }
        }

        return $global;
    }

    /**
     * Updates DB views
     */
    protected function _updateViews() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $metaData = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);
        $metaData->updateViews();
    }

}
