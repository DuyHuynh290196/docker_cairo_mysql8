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
 * Class manages category rights to buy
 */
class CategoryRightsBuyableAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array('container1' => array( // field , table,  visible, multilanguage, ident
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
     * Returns SQL query for data to fetc
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getQuery" in next major
     */
    protected function _getQuery() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        // looking for table/view
        $groupTable = $this->_getViewName('oxgroups');
        $action = 2;
        $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $artId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        $synchArtId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$artId) {
            $sqlAdd = " from $groupTable where 1 ";
        } else {
            // fetching article RR view index
            $sqlAdd = " from $groupTable, oxobjectrights where ";
            $sqlAdd .= " oxobjectrights.oxobjectid = " . $db->quote($artId) . " and ";
            $sqlAdd .= " oxobjectrights.oxoffset = ( $groupTable.oxrrid div 31 ) and ";
            $sqlAdd .= " oxobjectrights.oxgroupidx & ( 1 << ( $groupTable.oxrrid mod 31 ) ) and oxobjectrights.oxaction = $action ";
        }

        if ($synchArtId && $synchArtId != $artId) {
            $sqlAdd = " from $groupTable left join oxobjectrights on ";
            $sqlAdd .= " oxobjectrights.oxoffset = ($groupTable.oxrrid div 31) and ";
            $sqlAdd .= " oxobjectrights.oxgroupidx & ( 1 << ( $groupTable.oxrrid mod 31 ) ) and oxobjectrights.oxobjectid=" . $db->quote($synchArtId) . " and oxobjectrights.oxaction = $action ";
            $sqlAdd .= " where oxobjectrights.oxobjectid != " . $db->quote($synchArtId) . " or ( oxobjectid is null )";
        }

        return $sqlAdd;
    }

    /**
     * Removing article from Buy Article group list
     */
    public function removeGroupFromCatBuy()
    {
        $config = $this->getConfig();

        $groups = $this->_getActionIds('oxgroups.oxrrid');
        $oxId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');

        $range = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxrrapplyrange');
        $action = 2;

        // removing all
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupTable = $this->_getViewName('oxgroups');
            $groups = $this->_getAll($this->_addFilter("select $groupTable.oxrrid " . $this->_getQuery()));
        }

        if ($oxId != "-1" && isset($oxId) && count($groups)) {
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

            $cat = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
            $cat->load($oxId);

            $shopID = $config->getShopID();
            $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

            $object2categoryViewName = $this->_getViewName('oxobject2category');

            // iterating through indexes and applying to (sub)categories R&R
            foreach ($indexes as $offset => $idx) {
                // processing category
                $sqlQuery = "update oxobjectrights set oxgroupidx = oxgroupidx & ~:id where oxobjectid = :oxobjectid and oxoffset = :oxoffset and oxaction = :oxaction";
                $db->execute($sqlQuery, [
                    ':id' => $idx,
                    ':oxobjectid' => $oxId,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action
                ]);

                // processing articles
                $sqlQuery = "update oxobjectrights set oxgroupidx = oxgroupidx & ~:id where oxaction = :oxaction and oxoffset = :oxoffset and oxobjectid in ( ";
                $sqlQuery .= "select oxobject2category.oxobjectid from  $object2categoryViewName as oxobject2category 
                    where oxobject2category.oxcatnid = :oxcatnid ) ";
                $db->execute($sqlQuery, [
                    ':id' => $idx,
                    ':oxaction' => $action,
                    ':oxoffset' => $offset,
                    ':oxcatnid' => $oxId
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
                        ':id' => $idx,
                        ':oxshopid' => $shopID,
                        ':oxoffset' => $offset,
                        ':oxaction' => $action,
                        ':oxleft' => $cat->oxcategories__oxleft->value,
                        ':oxright' => $cat->oxcategories__oxright->value,
                        ':oxrootid' => $cat->oxcategories__oxrootid->value
                    ]);

                    // processing articles
                    $sqlQuery = "update oxobjectrights set oxobjectrights.oxgroupidx = oxobjectrights.oxgroupidx & ~:id ";
                    $sqlQuery .= "where oxobjectrights.oxaction = :oxaction and oxobjectrights.oxobjectid in ( ";
                    $sqlQuery .= "select oxobject2category.oxobjectid from $object2categoryViewName as oxobject2category ";
                    $sqlQuery .= "left join oxcategories on oxcategories.oxid = oxobject2category.oxcatnid ";
                    $sqlQuery .= "inner join oxcategories2shop as t2s on t2s.oxmapobjectid = oxcategories.oxmapid where t2s.oxshopid = :oxshopid ";
                    $sqlQuery .= "where oxcategories.oxrootid = :oxrootid and ";
                    $sqlQuery .= "oxcategories.oxleft > :oxleft and ";
                    $sqlQuery .= "oxcategories.oxright < :oxright ) ";
                    $db->execute($sqlQuery, [
                        ':id' => $idx,
                        ':oxaction' => $action,
                        ':oxshopid' => $shopID,
                        ':oxrootid' => $cat->oxcategories__oxrootid->value,
                        ':oxleft' => $cat->oxcategories__oxleft->value,
                        ':oxright' => $cat->oxcategories__oxright->value
                    ]);
                }
            }

            // removing cleared
            $sqlQuery = "delete from oxobjectrights where oxgroupidx = 0";
            $db->Execute($sqlQuery);

            $this->flushCategoryDependencies($oxId);
        }
    }

    /**
     * Adding article to Buy Article group list
     */
    public function addGroupToCatBuy()
    {
        $config = $this->getConfig();

        $groups = $this->_getActionIds('oxgroups.oxrrid');
        $oxId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        $range = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxrrapplyrange');
        $action = 2;

        // adding
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupTable = $this->_getViewName('oxgroups');
            $groups = $this->_getAll($this->_addFilter("select $groupTable.oxrrid " . $this->_getQuery()));
        }

        if ($oxId != "-1" && isset($oxId) && count($groups)) {
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
            $category->load($oxId);

            $shopID = $config->getShopID();
            $object2categortyViewName = $this->_getViewName('oxobject2category');
            $utilsObject = Registry::getUtilsObject();
            $db = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

            // iterating through indexes and applying to (sub)categories R&R
            foreach ($indexes as $offset => $idx) {
                // processing category
                $sqlQuery = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) 
                                values (:oxid, :oxobjectid, :oxgroupidx, :oxoffset, :oxaction)
                                on duplicate key update oxgroupidx = (oxgroupidx | :oxgroupidx)";
                $db->execute($sqlQuery, [
                    ':oxid' => $utilsObject->generateUID(),
                    ':oxobjectid' => $oxId,
                    ':oxgroupidx' => $idx,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action
                ]);

                // processing articles
                $sqlQuery = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) ";
                $sqlQuery .= "select md5( concat( a.oxobjectid, oxobject2category.oxid) ), oxobject2category.oxobjectid, a.oxgroupidx, a.oxoffset, a.oxaction ";
                $sqlQuery .= "from $object2categortyViewName as oxobject2category left join oxobjectrights a on oxobject2category.oxcatnid=a.oxobjectid where oxobject2category.oxcatnid = :oxcatnid and a.oxaction = :oxaction ";
                $sqlQuery .= "on duplicate key update oxobjectrights.oxgroupidx = (oxobjectrights.oxgroupidx | a.oxgroupidx ) ";
                $db->Execute($sqlQuery, [
                    ':oxcatnid' => $oxId,
                    ':oxaction' => $action
                ]);

                if ($range) {
                    // processing subcategories
                    $sqlQuery = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) ";
                    $sqlQuery .= "select :oxid, oxcategories.oxid, :oxgroupidx, :oxoffset, :oxaction from oxcategories ";
                    $sqlQuery .= "inner join oxcategories2shop as t2s on t2s.oxmapobjectid = oxcategories.oxmapid where t2s.oxshopid = :oxshopid ";
                    $sqlQuery .= "where oxcategories.oxleft > :oxleft and oxcategories.oxright < :oxright and ";
                    $sqlQuery .= "oxcategories.oxrootid = :oxrootid ";
                    $sqlQuery .= "on duplicate key update oxgroupidx = (oxgroupidx | :oxgroupidx) ";
                    $db->execute($sqlQuery, [
                        ':oxid' => $utilsObject->generateUID(),
                        ':oxgroupidx' => $idx,
                        ':oxoffset' => $offset,
                        ':oxaction' => $action,
                        ':oxshopid' => $shopID,
                        ':oxleft' => $category->oxcategories__oxleft->value,
                        ':oxright' => $category->oxcategories__oxright->value,
                        ':oxrootid' => $category->oxcategories__oxrootid->value
                    ]);

                    // processing articles
                    $sqlQuery = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) ";
                    $sqlQuery .= "select md5( concat( a.oxobjectid, oxobject2category.oxid, a.oxaction, a.oxoffset) ), oxobject2category.oxobjectid, a.oxgroupidx, a.oxoffset, a.oxaction ";
                    $sqlQuery .= "from $object2categortyViewName oxobject2category ";
                    $sqlQuery .= "left join oxcategories on oxcategories.oxid = oxobject2category.oxcatnid ";
                    $sqlQuery .= "left join oxobjectrights a on a.oxobjectid = :oxobjectid ";
                    $sqlQuery .= "where oxcategories.oxrootid = :oxrootid and ";
                    $sqlQuery .= "oxcategories.oxleft > :oxleft and  ";
                    $sqlQuery .= "oxcategories.oxright < :oxright and a.oxaction = :oxaction ";
                    $sqlQuery .= "on duplicate key update oxobjectrights.oxgroupidx = (oxobjectrights.oxgroupidx | a.oxgroupidx )";
                    $db->Execute($sqlQuery, [
                        ':oxobjectid' => $oxId,
                        ':oxrootid' => $category->oxcategories__oxrootid->value,
                        ':oxleft' => $category->oxcategories__oxleft->value,
                        ':oxright' => $category->oxcategories__oxright->value,
                        ':oxaction' => $action
                    ]);
                }
            }

            $this->flushCategoryDependencies($oxId);
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
