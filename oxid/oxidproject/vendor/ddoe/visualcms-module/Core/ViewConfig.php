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

namespace OxidEsales\VisualCmsModule\Core;

use OxidEsales\VisualCmsModule\Application\Model\Media;

use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Application\Model\ManufacturerList;
use OxidEsales\Eshop\Core\Theme as OxidTheme;

/**
 * Class ViewConfig
 *
 * @mixin \OxidEsales\Eshop\Core\ViewConfig
 */
class ViewConfig extends ViewConfig_parent
{
    protected $_aNewArticleList = null;

    protected $_aTopArticleList = null;

    protected $_aCategoryArticleList = array();


    public function getNewestArticles( $iLimit = 6 )
    {
        if ( $this->_aNewArticleList === null )
        {
            $this->_aNewArticleList = array();

            /** @var ArticleList $oArtList */
            $oArtList = oxNew( ArticleList::class );
            $oArtList->loadNewestArticles( $iLimit );

            if ( $oArtList->count() )
            {
                $this->_aNewArticleList = $oArtList;
            }
        }

        return $this->_aNewArticleList;
    }


    public function getTopArticleList( $iLimit = 6  )
    {
        if ( $this->_aTopArticleList === null )
        {
            $this->_aTopArticleList = false;

            /** @var ArticleList $oArtList */
            $oArtList = oxNew( ArticleList::class );
            $oArtList->loadActionArticles( 'OXTOPSTART', $iLimit );

            if ( $oArtList->count() )
            {
                $this->_aTopArticleList = $oArtList;
            }
        }

        return $this->_aTopArticleList;
    }


    public function getCategoryArticles( $sId, $iLimit = 6  )
    {
        if ( !$this->_aCategoryArticleList[ $sId ] )
        {
            $this->_aCategoryArticleList[ $sId ] = false;

            $sArticleTable = getViewName( 'oxarticles' );

            /** @var ArticleList $oArtList */
            $oArtList = oxNew( ArticleList::class );
            $oArtList->setCustomSorting( "$sArticleTable.oxsoldamount desc" );
            $oArtList->loadCategoryArticles( $sId, array(), $iLimit );

            if ( $oArtList->count() )
            {
                $this->_aCategoryArticleList[ $sId ] = $oArtList;
            }
        }

        return $this->_aCategoryArticleList[ $sId ];
    }


    public function getManufacturerlist()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        /** @var ManufacturerList $oManufacturerTree */
        $oManufacturerTree = oxNew( ManufacturerList::class );
        $oManufacturerTree->buildManufacturerTree( 'manufacturerlist', null, $oConfig->getShopHomeURL() );

        return $oManufacturerTree;

    }


    /**
     * Returns navigation forms parameters
     *
     * @return string
     */
    public function getNavFormParams()
    {
        $sParams = parent::getNavFormParams();

        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $oStr = getStr();

        if( ( $sContentId = $oConfig->getRequestParameter('oxcid') ) )
        {
            $sParams .= '<input type="hidden" name="oxcid" value="' . $oStr->htmlentities( $sContentId ) . '" />' . "\n";
        }
        elseif( ( $sLoadId = $oConfig->getRequestParameter('oxloadid') ) )
        {
            $sParams .= '<input type="hidden" name="oxloadid" value="' . $oStr->htmlentities( $sLoadId ) . '" />' . "\n";
        }

        return $sParams;
    }


    public function isFlowTheme()
    {
        /** @var Theme $oTheme */
        $oTheme = oxNew( OxidTheme::class );
        $oTheme->load( $oTheme->getActiveThemeId() );

        if ($oTheme->getId() == 'flow') {
            return true;
        }

        if (($oParentTheme = $oTheme->getParent()) && ($oParentTheme->getId() == 'flow')) {
            return true;
        }

        return false;
    }


    public function isRoxiveTheme()
    {
        /** @var Theme $oTheme */
        $oTheme = oxNew( OxidTheme::class );
        $oTheme->load( $oTheme->getActiveThemeId() );

        if ($oTheme->getId() == 'dd_roxive' || $oTheme->getId() == 'flow') {
            return true;
        }

        if (($oParentTheme = $oTheme->getParent()) && ($oParentTheme->getId() == 'dd_roxive' || $oParentTheme->getId() == 'flow')) {
            return true;
        }

        return false;
    }


    public function isAzureTheme()
    {
        /** @var Theme $oTheme */
        $oTheme = oxNew( OxidTheme::class );
        $oTheme->load( $oTheme->getActiveThemeId() );

        if( $oTheme->getId() == 'azure' )
        {
            return true;
        }

        if( ( $oParentTheme = $oTheme->getParent() ) && $oParentTheme->getId() == 'azure' )
        {
            return true;
        }

        return false;
    }


    /**
     * return url to the requested module file
     *
     * @param string $sModule module name (directory name in modules dir)
     * @param string $sFile   file name to lookup
     *
     * @throws \oxFileException
     *
     * @return string
     */
    public function getModuleUrl( $sModule, $sFile = '')
    {
        $sUrl = parent::getModuleUrl( $sModule, $sFile );

        if( \OxidEsales\Eshop\Core\Registry::getConfig()->isSsl() )
        {
            $sUrl = str_replace( 'http:', 'https:', $sUrl );
        }

        return $sUrl;

    }


    public function getMediaUrl( $sFile = '' )
    {
        /** @var Media $oMedia */
        $oMedia = oxNew( Media::class );
        return $oMedia->getMediaUrl( $sFile );
    }

}
