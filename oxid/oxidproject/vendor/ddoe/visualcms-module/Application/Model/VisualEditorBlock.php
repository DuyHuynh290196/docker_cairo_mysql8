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

namespace OxidEsales\VisualCmsModule\Application\Model;

use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Article;

/**
 * Class VisualEditorBlock
 *
 * @mixin \OxidEsales\Eshop\Core\Model\BaseModel
 */
class VisualEditorBlock extends BaseModel
{
    protected $_sArticleId = null;

    protected $_sCategoryId = null;

    protected $_sManufacturerId = null;

    protected $_aBlocks = null;

    protected $_aCurrentBlocks = null;


    public function getBlocks()
    {
        if( $this->_aBlocks == null )
        {
            $this->_setCategoryId();
            $this->_setManufacturerId();
            $this->_setArticleId();

            $aBlocks = array();
            $sContentTable = getViewName( 'oxcontents' );

            $sSelect = "SELECT `OXCONTENT`, `DDBLOCK`, `DDOBJECTTYPE`, `DDOBJECTID` FROM `" . $sContentTable . "` WHERE `DDISBLOCK` = 1 AND `OXACTIVE` = 1 ORDER BY FIELD( `DDOBJECTTYPE`, 'article', 'category', 'manufacturer', 'empty' )";
            $aData = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC )->getAll( $sSelect );

            //$oSmarty = \OxidEsales\Eshop\Core\Registry::get( 'oxUtilsView' )->getSmarty();

            $aFilteredBlocksFound = array();

            if( $aData )
            {
                foreach( $aData as $aRow )
                {
                    if( in_array( $aRow[ 'DDBLOCK' ], $aFilteredBlocksFound ) )
                    {
                        continue;
                    }

                    if( ( $aRow[ 'DDOBJECTTYPE' ] == 'empty' ) ||
                        ( $aRow[ 'DDOBJECTID' ] &&
                            ( $aRow[ 'DDOBJECTTYPE' ] == 'article' && $aRow[ 'DDOBJECTID' ] == $this->_sArticleId ) ||
                            ( $aRow[ 'DDOBJECTTYPE' ] == 'category' && $aRow[ 'DDOBJECTID' ] == $this->_sCategoryId ) ||
                            ( $aRow[ 'DDOBJECTTYPE' ] == 'manufacturer' && $aRow[ 'DDOBJECTID' ] == $this->_sManufacturerId )
                        ) )
                    {
                        $aFilteredBlocksFound[] = $aRow[ 'DDBLOCK' ];
                    }
                    else
                    {
                        continue;
                    }

                    $aBlocks[ $aRow[ 'DDBLOCK' ] ] = $aRow[ 'OXCONTENT' ];
                }
            }

            $this->_aBlocks = $aBlocks;
        }

        return $this->_aBlocks;

    }

    public function getBlock( $sBlockName )
    {
        if( $this->_aCurrentBlocks == null || !$this->_aCurrentBlocks[ $sBlockName ]  )
        {
            $this->_setCategoryId();
            $this->_setManufacturerId();
            $this->_setArticleId();

            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
            $sContentTable = getViewName( 'oxcontents' );

            $sSelect = "SELECT
                          `OXCONTENT`
                        FROM `" . $sContentTable . "`
                        WHERE `DDISBLOCK` = 1 AND `OXACTIVE` = 1 AND `DDBLOCK` = " . $oDb->quote( $sBlockName ) . "
                          AND (
                                ( `DDOBJECTTYPE` = 'article' && `DDOBJECTID` = '" . $this->_sArticleId . "' ) OR
                                ( `DDOBJECTTYPE` = 'category' && `DDOBJECTID` = '" . $this->_sCategoryId . "' ) OR
                                ( `DDOBJECTTYPE` = 'manufacturer' && `DDOBJECTID` = '" . $this->_sManufacturerId . "' ) OR
                                ( `DDOBJECTTYPE` = 'empty' )
                              )
                        ORDER BY FIELD( `DDOBJECTTYPE`, 'article', 'category', 'manufacturer', 'empty' )";

            if( ( $sContent = $oDb->getOne( $sSelect ) ) )
            {
                $this->_aCurrentBlocks[ $sBlockName ] = $sContent;
            }
        }

        return $this->_aCurrentBlocks[ $sBlockName ];
    }


    protected function _setArticleId()
    {
        if( $this->_sArticleId == null )
        {
            $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
            $this->_sArticleId = false;

            $aExcludedViews = array( 'alist', 'search', 'manufacturerlist' );

            if ( !in_array( $oConfig->getTopActiveView()->getClassName(), $aExcludedViews ) && ( ( $sId = $oConfig->getRequestParameter( 'anid' ) ) || ( $sId = $oConfig->getRequestParameter( 'aid' ) ) ) )
            {
                /** @var oxArticle $oArticle */
                $oArticle = oxNew( Article::class );

                if( $oArticle->load( $sId ) )
                {
                    $this->_sArticleId = $sId;

                    $oCategory = $oArticle->getCategory();

                    if( $this->_sCategoryId == null && $oCategory )
                    {
                        $this->_sCategoryId = $oCategory->getId();
                    }

                    if( $this->_sManufacturerId == null )
                    {
                    $this->_sManufacturerId = $oArticle->getManufacturerId();
                    }
                }
            }
        }

    }


    protected function _setCategoryId()
    {
        if( $this->_sCategoryId == null )
        {
            $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
            $this->_sCategoryId = false;

            if( ( $sId = $oConfig->getRequestParameter( 'cnid' ) ) )
            {
                $this->_sCategoryId = $sId;
            }
        }
    }


    protected function _setManufacturerId()
    {
        if( $this->_sManufacturerId == null )
        {
            $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
            $this->_sManufacturerId = false;

            if( ( $sId = $oConfig->getRequestParameter( 'mnid' ) ) )
            {
                $this->_sManufacturerId = $sId;
            }
        }
    }

}
