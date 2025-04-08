<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use ajaxListComponent;
use OxidEsales\Eshop\Core\Registry;
use oxDb;
use oxUtilsObject;

/**
 * Class manages category rights to view
 */
class CategoryRightsVisibleAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    const NEW_CATEGORY_ID = '-1';
    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array(
        'container1' => array( // field , table,  visible, multilanguage, ident
            array('oxtitle', 'oxgroups', 1, 0, 0),
            array('oxid', 'oxgroups', 0, 0, 0),
            array('oxrrid', 'oxgroups', 0, 0, 1),
        ),
        'container2' => array(
            array('oxtitle', 'oxgroups', 1, 0, 0),
            array('oxid', 'oxgroups', 0, 0, 0),
            array('oxrrid', 'oxgroups', 0, 0, 1),
        )
    );

    /**
     * Returns SQL query for data to fetch
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getQuery" in next major
     */
    protected function _getQuery() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $RRId = null;
        $action = 1;
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // looking for table/view
        $groupTable = $this->_getViewName('oxgroups');

        $catId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        $synchCatId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$catId) {
            $queryAdd = " from $groupTable where 1 ";
        } else {
            // fetching category RR view index
            $queryAdd = " from $groupTable, oxobjectrights where ";
            $queryAdd .= " oxobjectrights.oxobjectid = " . $db->quote($catId) . " and ";
            $queryAdd .= " oxobjectrights.oxoffset = ($groupTable.oxrrid div 31) and ";
            $queryAdd .= " oxobjectrights.oxgroupidx & (1 << ($groupTable.oxrrid mod 31)) and oxobjectrights.oxaction = $action ";
        }

        if ($synchCatId && $synchCatId != $catId) {
            $queryAdd = " from $groupTable left join oxobjectrights on ";
            $queryAdd .= " oxobjectrights.oxoffset = ($groupTable.oxrrid div 31) and ";
            $queryAdd .= " oxobjectrights.oxgroupidx & (1 << ($groupTable.oxrrid mod 31)) and oxobjectrights.oxobjectid=" . $db->quote($synchCatId) . " and oxobjectrights.oxaction = $action ";
            $queryAdd .= " where oxobjectrights.oxobjectid != " . $db->quote($synchCatId) . " or (oxobjectid is null)";
        }

        return $queryAdd;
    }

    /**
     * Removing article from View Article group
     */
    public function removeGroupFromCatView()
    {
        $config = $this->getConfig();

        $range = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxrrapplyrange');

        $groups = $this->_getActionIds('oxgroups.oxrrid');
        $categoryId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');

        $action = 1;

        // removing all
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupTable = $this->_getViewName('oxgroups');
            $groups = $this->_getAll($this->_addFilter("select $groupTable.oxrrid " . $this->_getQuery()));
        }

        if ($categoryId != self::NEW_CATEGORY_ID && isset($categoryId) && is_array($groups) && count($groups)) {
            $indexes = array();
            foreach ($groups as $RRIdx) {
                $offset = (int) ($RRIdx / 31);
                $bitMap = 1 << ($RRIdx % 31);

                // summing indexes
                if (!isset($indexes[$offset])) {
                    $indexes[$offset] = $bitMap;
                } else {
                    $indexes[$offset] = $indexes [$offset] | $bitMap;
                }
            }

            $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
            $category->load($categoryId);

            $shopID = $config->getShopID();
            $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

            $object2categoryViewName = $this->_getViewName('oxobject2category');

            // iterating through indexes and applying to (sub)categories R&R
            foreach ($indexes as $offset => $sIdx) {
                // processing category
                $sqlQuery = "update oxobjectrights set oxgroupidx = oxgroupidx & ~:id where oxobjectid = :oxobjectid and oxoffset = :oxoffset and oxaction = :oxaction";
                $db->execute($sqlQuery, [
                    ':id' => $sIdx,
                    ':oxobjectid' => $categoryId,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action
                ]);

                // processing articles
                $sqlQuery = "update oxobjectrights set oxgroupidx = oxgroupidx & ~:id where oxaction = :oxaction and oxoffset = :oxoffset and oxobjectid in ( ";
                $sqlQuery .= "select oxobject2category.oxobjectid from $object2categoryViewName as oxobject2category 
                    where oxobject2category.oxcatnid = :oxcatnid ) ";
                $db->execute($sqlQuery, [
                    ':id' => $sIdx,
                    ':oxaction' => $action,
                    ':oxoffset' => $offset,
                    ':oxcatnid' => $categoryId
                ]);

                if ($range) {
                    // processing subcategories
                    $sqlQuery = "update oxobjectrights, oxcategories ";
                    $sqlQuery .= "inner join oxcategories2shop as t2s on t2s.oxmapobjectid = oxcategories.oxmapid where t2s.oxshopid = :oxshopid ";
                    $sqlQuery .= "set oxobjectrights.oxgroupidx = oxobjectrights.oxgroupidx & ~:id where oxobjectrights.oxoffset = :oxoffset and oxobjectrights.oxaction = :oxaction ";
                    $sqlQuery .= "and oxobjectrights.oxobjectid = oxcategories.oxid and ";
                    $sqlQuery .= "oxcategories.oxleft > :oxleft and oxcategories.oxright < :oxright and ";
                    $sqlQuery .= "oxcategories.oxrootid = :oxrootid";
                    $db->execute($sqlQuery, [
                        ':id' => $sIdx,
                        ':oxshopid' => $shopID,
                        ':oxoffset' => $offset,
                        ':oxaction' => $action,
                        ':oxleft' => $category->oxcategories__oxleft->value,
                        ':oxright' => $category->oxcategories__oxright->value,
                        ':oxrootid' => $category->oxcategories__oxrootid->value
                    ]);

                    // processing articles
                    $sqlQuery = "update oxobjectrights set oxobjectrights.oxgroupidx = oxobjectrights.oxgroupidx & ~:id ";
                    $sqlQuery .= "where oxobjectrights.oxaction = :oxaction and oxobjectrights.oxobjectid in ( ";
                    $sqlQuery .= "select oxobject2category.oxobjectid from $object2categoryViewName oxobject2category ";
                    $sqlQuery .= "left join oxcategories on oxcategories.oxid = oxobject2category.oxcatnid ";
                    $sqlQuery .= "inner join oxcategories2shop as t2s on t2s.oxmapobjectid = oxcategories.oxmapid where t2s.oxshopid = :oxshopid ";
                    $sqlQuery .= "where oxcategories.oxrootid = :oxrootid and ";
                    $sqlQuery .= "oxcategories.oxleft > :oxleft and ";
                    $sqlQuery .= "oxcategories.oxright < :oxright ";
                    $sqlQuery .= ") ";
                    $db->execute($sqlQuery, [
                        ':id' => $sIdx,
                        ':oxaction' => $action,
                        ':oxshopid' => $shopID,
                        ':oxrootid' => $category->oxcategories__oxrootid->value,
                        ':oxleft' => $category->oxcategories__oxleft->value,
                        ':oxright' => $category->oxcategories__oxright->value
                    ]);
                }
            }

            // removing cleared
            $sqlQuery = "delete from oxobjectrights where oxgroupidx = 0";
            $db->Execute($sqlQuery);

            $this->flushCategoryDependencies($categoryId);
        }
    }

    /**
     * Adding article to View Article group list
     */
    public function addGroupToCatView()
    {
        $config = $this->getConfig();

        $groups = $this->_getActionIds('oxgroups.oxrrid');
        $categoryId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        $range = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxrrapplyrange');
        $action = 1;

        // adding
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupTable = $this->_getViewName('oxgroups');
            $groups = $this->_getAll($this->_addFilter("select $groupTable.oxrrid " . $this->_getQuery()));
        }

        if ($categoryId != self::NEW_CATEGORY_ID && isset($categoryId) && is_array($groups) && count($groups)) {
            $indexes = array();
            foreach ($groups as $RRIdx) {
                $offset = (int) ($RRIdx / 31);
                $bitMap = 1 << ($RRIdx % 31);

                // summing indexes
                if (!isset($indexes[$offset])) {
                    $indexes[$offset] = $bitMap;
                } else {
                    $indexes[$offset] = $indexes [$offset] | $bitMap;
                }
            }

            $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
            $category->load($categoryId);

            $shopID = $config->getShopID();
            $object2categoryView = $this->_getViewName('oxobject2category');
            $utilsObject = Registry::getUtilsObject();
            $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

            // iterating through indexes and applying to (sub)categories R&R
            foreach ($indexes as $offset => $sIdx) {
                // processing category
                $query = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) 
                            values (:oxid, :oxobjectid, :oxgroupidx, :oxoffset, :oxaction)
                            on duplicate key update oxgroupidx = (oxgroupidx | :oxgroupidx)";
                $oDb->execute($query, [
                    ':oxid' => $utilsObject->generateUID(),
                    ':oxobjectid' => $categoryId,
                    ':oxgroupidx' => $sIdx,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action
                ]);

                $query = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) ";
                $query .= "select md5( concat( a.oxobjectid, oxobject2category.oxid) ), oxobject2category.oxobjectid, a.oxgroupidx, a.oxoffset, a.oxaction ";
                $query .= "from $object2categoryView as oxobject2category left join oxobjectrights a on oxobject2category.oxcatnid=a.oxobjectid where oxobject2category.oxcatnid = :oxcatnid and a.oxaction = :oxaction ";
                $query .= "on duplicate key update oxobjectrights.oxgroupidx = (oxobjectrights.oxgroupidx | a.oxgroupidx ) ";
                $oDb->Execute($query, [
                    ':oxcatnid' => $categoryId,
                    ':oxaction' => $action
                ]);

                if ($range) {
                    // processing subcategories
                    $query = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) ";
                    $query .= "select :oxid, oxcategories.oxid, :oxgroupidx, :oxoffset, :oxaction from oxcategories ";
                    $query .= "inner join oxcategories2shop as t2s on t2s.oxmapobjectid = oxcategories.oxmapid where t2s.oxshopid = :oxshopid ";
                    $query .= "where oxcategories.oxleft > :oxleft and oxcategories.oxright < :oxright and ";
                    $query .= "oxcategories.oxrootid = :oxrootid ";
                    $query .= "on duplicate key update oxgroupidx = (oxgroupidx | :oxgroupidx) ";
                    $oDb->execute($query, [
                        ':oxid' => $utilsObject->generateUID(),
                        ':oxgroupidx' => $sIdx,
                        ':oxoffset' => $offset,
                        ':oxaction' => $action,
                        ':oxshopid' => $shopID,
                        ':oxleft' => $category->oxcategories__oxleft->value,
                        ':oxright' => $category->oxcategories__oxright->value,
                        ':oxrootid' => $category->oxcategories__oxrootid->value
                    ]);

                    // processing articles
                    $query = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) ";
                    $query .= "select md5( concat( a.oxobjectid, oxobject2category.oxid, a.oxaction, a.oxoffset) ), oxobject2category.oxobjectid, a.oxgroupidx, a.oxoffset, a.oxaction ";
                    $query .= "from $object2categoryView as oxobject2category ";
                    $query .= "left join oxcategories on oxcategories.oxid = oxobject2category.oxcatnid ";
                    $query .= "left join oxobjectrights a on a.oxobjectid = :oxobjectid ";
                    $query .= "inner join oxarticles2shop as t2s on t2s.oxmapobjectid = oxarticles.oxmapid where t2s.oxshopid = :oxshopid ";
                    $query .= "where oxcategories.oxrootid = :oxrootid and ";
                    $query .= "oxcategories.oxleft > :oxleft and  ";
                    $query .= "oxcategories.oxright < :oxright and a.oxaction = :oxaction ";
                    $query .= "on duplicate key update oxobjectrights.oxgroupidx = (oxobjectrights.oxgroupidx | a.oxgroupidx )";
                    $oDb->Execute($query, [
                        ':oxobjectid' => $categoryId,
                        ':oxshopid' => $shopID,
                        ':oxrootid' => $category->oxcategories__oxrootid->value,
                        ':oxleft' => $category->oxcategories__oxleft->value,
                        ':oxright' => $category->oxcategories__oxright->value,
                        ':oxaction' => $action
                    ]);
                }
            }

            $this->flushCategoryDependencies($categoryId);
        }
    }

    /**
     * @param string $categoryId
     */
    protected function flushCategoryDependencies($categoryId)
    {
        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        $category->executeDependencyEvent(array($categoryId));
    }
}
