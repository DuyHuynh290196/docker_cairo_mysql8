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
use OxidEsales\Eshop\Application\Model\Category;

class tabs_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_TABS';

    protected $_sBackgroundColor = '#f1c40f';

    protected $_sIcon = 'fa-folder';

    public function install()
    {
        $this->setShortCode(basename(__FILE__, '.php'));

        // set critical frontend options
        $this->setOptions(
            [
                'tabs' => [
                    'type' => 'multi',
                ]
            ]
        );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();
        $oViewConfig = Registry::get( 'oxViewConfig' );

        $aCategories = array();

        if( $this->isAdmin() )
        {
            $aCategories = $this->getCategories( $oLang->getEditLanguage() );
        }

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
                'tabs'      => array(
                    'type'       => 'multi',
                    'label'      => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TABS' ),
                    'values'     => array(
                        $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TABS_CATEGORY_SPECIAL' )    => array(
                            'top' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TABS_TOP' ),
                            'new' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TABS_NEW' )
                        ),
                        $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TABS_CATEGORY_CATEGORIES' ) => $aCategories
                    ),
                    'dataFields' => array(
                        'name' => 'label'
                    )
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
                'style'     => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_STYLE' ),
                    'values' => array(
                        'tabs'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_STYLE_TABS' ),
                        'pills' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_STYLE_PILLS' )
                    ),
                    'value'  => 'tabs'
                ),
                'animation' => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ANIMATION' ),
                    'values' => array(
                        0      => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ANIMATION_NONE' ),
                        'fade' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ANIMATION_FADE' )
                    ),
                    'value'  => 0
                ),
                'justified' => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_JUSTIFIED' ),
                ),
                'name'      => array(
                    'type'    => 'hidden',
                    'preview' => true
                )
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        $oLang        = Registry::getLang();
        $oViewConf    = Registry::get( 'oxViewConfig' );
        $blAzure      = $oViewConf->isAzureTheme();
        $sTabHTML     = '';
        $sContentHTML = '';
        $blFirst      = true;

        $aTabTitles = ( $aParams[ 'name' ] ? explode( ',', $aParams[ 'name' ] ) : array() );

        if( !$aParams[ 'count' ] )
        {
            $aParams[ 'count' ] = 6;
        }

        if( $aParams[ 'count' ] >= 4 )
        {
            $sColSize = 'col-xs-12 col-sm-6 col-md-' . floor( 12 / $aParams[ 'count' ] );
        }
        else
        {
            $sColSize = 'col-xs-12 col-sm-' . floor( 12 / $aParams[ 'count' ] );
        }

        foreach( $aParams[ 'tabs' ] as $iKey => $sTab )
        {
            $sTabTitle = '';

            switch( $sTab )
            {
                case 'top':
                    $sTabTitle = $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TABS_TOP' );
                    $aArticles = $oViewConf->getTopArticleList( $aParams[ 'count' ] );
                    break;

                case 'new':
                    $sTabTitle = $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TABS_NEW' );
                    $aArticles = $oViewConf->getNewestArticles( $aParams[ 'count' ] );
                    break;

                default:
                    $oCategory = oxNew( Category::class );

                    if( $oCategory->load( $sTab ) )
                    {
                        $sTabTitle = $oCategory->oxcategories__oxtitle->value;
                    }
                    elseif( $aTabTitles[ $iKey ] )
                    {
                        $_aTitleTree = explode( '>', $aTabTitles[ $iKey ] );
                        $_aTitleTree = array_reverse( $_aTitleTree );
                        $sTabTitle = trim( $_aTitleTree[ 0 ] );
                    }

                    $aArticles = $oViewConf->getCategoryArticles( $sTab, $aParams[ 'count' ] );
                    break;

            }

            $_sContent = '';

            if( $aArticles )
            {
                if( !$blAzure )
                {
                    $sListType  = ( $aParams[ 'articletype' ] ? $aParams[ 'articletype' ] : 'infogrid' );
                    $_sContent .= '<div class="row infogridView">';
                }
                else
                {
                    $sListType  = ( $aParams[ 'articletype' ] ? $aParams[ 'articletype' ] : 'grid' );
                    $_sContent .= '<ul class="gridView clear">';
                }

                /** @var oxArticle $oArticle */
                foreach( $aArticles as $oArticle )
                {
                    if( !$blAzure )
                    {
                        $_sContent .= '<div class="productBox productData ' . $sColSize . '">';
                    }
                    else
                    {
                        $_sContent .= '<li class="productData">';
                    }

                    $_sContent .= '[{oxid_include_widget cl="oxwArticleBox" _parent=$oView->getClassName() _navurlparams=$oViewConf->getNavUrlParams() iLinkType="' . $oArticle->getLinkType() . '" anid="' . $oArticle->getId() . '" isVatIncluded=$oView->isVatIncluded() nocookie=1 sWidgetType=product sListType=listitem_' . $sListType . ' inlist=1 skipESIforUser=1}]';

                    if( !$blAzure )
                    {
                        $_sContent .= '</div>';
                    }
                    else
                    {
                        $_sContent .= '</li>';
                    }
                }

                if( !$blAzure )
                {
                    $_sContent .= '</div>';
                }
                else
                {
                    $_sContent .= '</ul>';
                }
            }

            $sUniqId       = uniqid();

            $sTabHTML     .= '<li role="presentation"' . ( $blFirst ? ' class="active"' : '' ) . '><a href="#tab_' . str_replace( '.', '', $sTab ) . '_' . $sUniqId . '" role="tab" data-toggle="tab">' . $sTabTitle . '</a></li>';
            $sContentHTML .= '<div role="tabpanel" class="tab-pane' . ( $aParams[ 'animation' ] && $aParams[ 'animation' ] == 'fade' ? ' fade' : '' ) . ( $blFirst ?  ( $aParams[ 'animation' ] && $aParams[ 'animation' ] == 'fade' ? ' in' : '' ) . ' active' : '' ) . '" id="tab_' . str_replace( '.', '', $sTab ) . '_' . $sUniqId . '">' . $_sContent . '</div>';

            if( $blFirst )
            {
                $blFirst = false;
            }

        }

        $sHTML = '<div role="tabpanel" class="dd-shortcode-' . $this->getShortCode() . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '"">
                      <!-- Nav tabs -->
                      <ul class="nav ' . ( $aParams[ 'style' ] && $aParams[ 'style' ] == 'pills' ? 'nav-pills' : 'nav-tabs' ) . ( $aParams[ 'justified' ] ? ' nav-justified' : '' ) . '" role="tablist">
                          ' . $sTabHTML . '
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content">
                          ' . $sContentHTML . '
                      </div>
                  </div>';


        return $sHTML;

    }


}