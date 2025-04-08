<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class manages article rights to buy
 */
class ArticleRightsBuyableAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = array(
        // field , table,  visible, multilanguage, ident
        'container1' => array(
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
        $groupTable = $this->_getViewName('oxgroups');
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $action = 2;

        $articleId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        $syncedArticleId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        // category selected or not ?
        if (!$articleId) {
            $query = " from $groupTable where 1 ";
        } else {
            // fetching article RR view index
            $query = " from {$groupTable}, oxobjectrights where " .
                     " oxobjectrights.oxobjectid = " . $database->quote($articleId) . " and " .
                     " oxobjectrights.oxoffset = ({$groupTable}.oxrrid div 31) and " .
                     " oxobjectrights.oxgroupidx & (1 << ({$groupTable}.oxrrid mod 31)) " .
                     "and oxobjectrights.oxaction = $action ";
        }

        if ($syncedArticleId && $syncedArticleId != $articleId) {
            $quotedSyncedArticleId = $database->quote($syncedArticleId);
            $query = " from {$groupTable} left join oxobjectrights " .
                     "on oxobjectrights.oxoffset = ( {$groupTable}.oxrrid div 31 ) " .
                     "and oxobjectrights.oxgroupidx & (1 << ( {$groupTable}.oxrrid mod 31 ) ) " .
                     "and oxobjectrights.oxobjectid= " . $database->quote($syncedArticleId) .
                     " and oxobjectrights.oxaction = $action " .
                     " where oxobjectrights.oxobjectid != {$quotedSyncedArticleId} or ( oxobjectid is null ) ";
        }

        return $query;
    }

    /**
     * Removing article from View Article group
     */
    public function removeGroupFromView()
    {
        $removeFromGroups = $this->_getActionIds('oxgroups.oxrrid');
        $articleId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        $action = 2;

        // removing all
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupTable = $this->_getViewName('oxgroups');
            $removeFromGroups = $this->_getAll($this->_addFilter("select $groupTable.oxrrid " . $this->_getQuery()));
        }

        if ($articleId != "-1" && isset($articleId) && is_array($removeFromGroups) && count($removeFromGroups)) {
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            $indexes = array();
            foreach ($removeFromGroups as $RRIdx) {
                $offset = (int) ($RRIdx / 31);
                $bitMap = 1 << ($RRIdx % 31);

                // summing indexes
                if (!isset($indexes[$offset])) {
                    $indexes[$offset] = $bitMap;
                } else {
                    $indexes[$offset] = $indexes [$offset] | $bitMap;
                }
            }
            // iterating through indexes and applying to (sub)categories R&R
            foreach ($indexes as $offset => $index) {
                // processing article
                $query = "update oxobjectrights set oxgroupidx = oxgroupidx & ~:index 
                            where oxobjectid = :oxobjectid
                            and oxoffset = :oxoffset and oxaction = :oxaction";
                $database->execute($query, [
                    ':index' => $index,
                    ':oxobjectid' => $articleId,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action
                ]);
            }

            // removing cleared
            $query = "delete from oxobjectrights where oxgroupidx = 0";
            $database->execute($query);

            $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
            $category->executeDependencyEvent();

            $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $article->setId($articleId);
            $article->executeDependencyEvent();
        }
    }

    /**
     * Adding article to View Article group list
     */
    public function addGroupToView()
    {
        $addToGroups = $this->_getActionIds('oxgroups.oxrrid');
        $articleId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');
        $action = 2;

        // adding
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $groupTable = $this->_getViewName('oxgroups');
            $addToGroups = $this->_getAll($this->_addFilter("select $groupTable.oxrrid " . $this->_getQuery()));
        }

        if ($articleId != "-1" && isset($articleId) && is_array($addToGroups) && count($addToGroups)) {
            $indexes = array();
            foreach ($addToGroups as $RRIdx) {
                $offset = (int) ($RRIdx / 31);
                $bitMap = 1 << ($RRIdx % 31);

                // summing indexes
                if (!isset($indexes[$offset])) {
                    $indexes[$offset] = $bitMap;
                } else {
                    $indexes[$offset] = $indexes [$offset] | $bitMap;
                }
            }

            // iterating through indexes and applying to (sub)categories R&R
            $utilsObject = \OxidEsales\Eshop\Core\Registry::getUtilsObject();
            $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
            foreach ($indexes as $offset => $index) {
                // processing category
                $sQ = "insert into oxobjectrights (oxid, oxobjectid, oxgroupidx, oxoffset, oxaction) 
                        values (:oxid, :oxobjectid, :oxgroupidx, :oxoffset, :oxaction)
                        on duplicate key update oxgroupidx = (oxgroupidx | :oxgroupidx)";
                $database->execute($sQ, [
                    ':oxid' => $utilsObject->generateUID(),
                    ':oxobjectid' => $articleId,
                    ':oxgroupidx' => $index,
                    ':oxoffset' => $offset,
                    ':oxaction' => $action,
                ]);
            }

            $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $article->setId($articleId);
            $article->executeDependencyEvent();
        }
    }
}
