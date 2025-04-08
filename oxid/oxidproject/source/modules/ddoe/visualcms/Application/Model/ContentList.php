<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version       OXID eSales Visual CMS
 */

namespace OxidEsales\VisualCmsModule\Application\Model;

/**
 * Class Content
 *
 * @mixin \OxidEsales\Eshop\Application\Model\ContentList
 */
class ContentList extends ContentList_parent
{
    /**
     * Calls parent and adapts SQL Query to make sure
     * only root level CMS-content pages are shown
     * in the category navigation
     *
     * @param integer $iType type.
     *
     * @return string
     */
    protected function _getSQLByType( $iType )
    {
        return explode( 'ORDER BY', parent::_getSQLByType($iType) )[0] . " AND `ddparentcontent` = '' ORDER BY `oxloadid`";
    }
}