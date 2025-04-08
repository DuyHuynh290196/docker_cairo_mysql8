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

use OxidEsales\VisualCmsModule\Application\Model\VisualEditorShortcode;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Actions;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Application\Model\ActionList;

class action_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_ACTION';

    protected $_sBackgroundColor = '#648AA7';

    protected $_sIcon = 'fa-tags';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();
        $oViewConfig = Registry::get( 'oxViewConfig' );

        if ( $oViewConfig->isAzureTheme() )
        {
            $aColumns = array(
                4  => '4 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' ),
                8  => '8 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' ),
                12 => '12 ' . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' )
            );
        }
        else
        {
            $aColumns = array(
                2  => '2 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' ),
                3  => '3 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' ),
                4  => '4 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' ),
                6  => '6 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' ),
                12 => '12 ' . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' )
            );
        }

        $this->setOptions(
            array(
                'id' => array(
                    'data'        => 'searchAction',
                    'type'        => 'select',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ACTION' ),
                    'placeholder' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CHOOSE_ACTION' ),
                    'dataFields'  => array(
                        'name' => 'label'
                    )

                ),
                'title'      => array(
                    'type'    => 'text',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ACTION_NAME' )
                ),
                'shortdesc' => array(
                    'type'    => 'textarea',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ACTION_SHORTDESC' ),
                ),
                'count'     => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE_COUNT' ),
                    'values' => $aColumns,
                    'value'  => 4
                ),
                'articletype'  => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE_TYPE' ),
                    'values' => array(
                        'grid'     => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE_TYPE_GRID' ),
                        'infogrid' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE_TYPE_INFOGRID' ),
                        'line'     => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE_TYPE_LINE' ),
                    ),
                    'value'  => 'infogrid'
                ),
                'name'      => array(
                    'type'    => 'hidden',
                    'preview' => true
                ),
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        /** @var Actions $oAction */
        /** @var ArticleList $oArtList */
        $oAction      = oxNew( Actions::class );
        $oArtList     = oxNew( ArticleList::class );

        $oViewConfig  = Registry::get( 'oxViewConfig' );
        $blAzure      = $oViewConfig->isAzureTheme();

        $sContent     = '';

        if( !$aParams[ 'count' ] )
        {
            $aParams[ 'count' ] = 6;
        }

        $oArtList->loadActionArticles( $aParams[ 'id' ], $aParams[ 'count' ] );

        if( $oAction->load( $aParams[ 'id' ] ) && $oArtList->count() > 0 )
        {
            $sContent = '<div class="dd-shortcode-' . $this->getShortCode() . ' ' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                            <div class="boxwrapper">';

            if( $aParams[ 'count' ] >= 4 )
            {
                $sColSize = 'col-xs-12 col-sm-6 col-md-' . floor( 12 / $aParams[ 'count' ] );
            }
            else
            {
                $sColSize = 'col-xs-12 col-sm-' . floor( 12 / $aParams[ 'count' ] );
            }

            if( $aParams[ 'title' ] )
            {
                $sContent .= '<div class="col-sm-12">
                                  <div class="page-header">
                                      <h2>' . $aParams[ 'title' ] . '</h2>';

                if( $aParams[ 'shortdesc' ] )
                {
                    $sContent .= '<small class="subhead">' . $aParams[ 'shortdesc' ] . '</small>';
                }

                $sContent .= '</div></div>';
            }

            $sContent .= '<div class="list-container">';

            if( !$blAzure )
            {
                $sListType = ( $aParams[ 'articletype' ] ? $aParams[ 'articletype' ] : 'infogrid' );
                $sContent .= '<div class="row ' . $sListType . 'View">';
            }
            else
            {
                $sListType = ( $aParams[ 'articletype' ] ? $aParams[ 'articletype' ] : 'grid' );
                $sContent .= '<ul class="' . $sListType . 'View clear">';
            }


            /** @var oxArticle $oActionArticle */
            foreach ( $oArtList->getArray() as $oActionArticle )
            {
                if( !$blAzure )
                {
                    $sContent .= '<div class="productBox productData ' . $sColSize . ' listitem-type-' . $sListType . '">';
                }
                else
                {
                    $sContent .= '<li class="productData">';
                }

                $sContent .= '[{oxid_include_widget cl="oxwArticleBox" _parent=$oView->getClassName() _navurlparams=$oViewConf->getNavUrlParams() iLinkType="' . $oActionArticle->getLinkType() . '" anid="' . $oActionArticle->getId() . '" isVatIncluded=$oView->isVatIncluded() nocookie=1 sWidgetType=product sListType=listitem_' . $sListType . ' inlist=1 skipESIforUser=1}]';

                if( !$blAzure )
                {
                    $sContent .= '</div>';
                }
                else
                {
                    $sContent .= '</li>';
                }
            }

            if( !$blAzure )
            {
                $sContent .= '</div>';
            }
            else
            {
                $sContent .= '</ul>';
            }

            $sContent .= '</div></div></div>';
        }

        return $sContent;
    }


    /**
     * Ermöglicht das Suchen von Aktionen
     */
    public function searchAction()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $aActions = array();

        if( $oConfig->getRequestParameter( 'value' ) )
        {
            /** @var Actions $oAction */
            $oAction = oxNew( Actions::class );
            $oAction->load( $oConfig->getRequestParameter( 'value' ) );

            $aActions[] = array(
                'value' => $oAction->getId(),
                'label' => $oAction->oxactions__oxtitle->value,
            );

        }
        elseif( $oConfig->getRequestParameter( 'search' ) )
        {
            $oDb = DatabaseProvider::getDb();
            $sSearch = $oConfig->getRequestParameter( 'search' );

            /** @var ActionList $oList */
            $oList = oxNew( ActionList::class );

            $sSelect = "SELECT
                            *
                        FROM `oxactions`
                        WHERE ( `OXSHOPID` = '" . $oConfig->getShopId() . "' OR `OXSHOPID` = '' )
                          AND `OXTITLE` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . "
                        ";

            $oList->selectString( $sSelect );

            /** @var oxActions $oAction */
            foreach( $oList as $oAction )
            {
                $aActions[] = array(
                    'value' => $oAction->getId(),
                    'label' => $oAction->oxactions__oxtitle->value,
                );
            }
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $aActions );
    }
}