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
use OxidEsales\Eshop\Application\Component\Widget\ArticleBox;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\ArticleList;

class article_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_ARTICLE';

    protected $_sBackgroundColor = '#e74c3c';

    protected $_sIcon = 'fa-newspaper-o';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'id'   => array(
                    'data'        => 'searchArticle',
                    'type'        => 'select',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ARTICLE' ),
                    'placeholder' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CHOOSE_ARTICLE' ),
                    'dataFields'  => array(
                        'name' => 'label'
                    )

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
                'name' => array(
                    'type'    => 'hidden',
                    'preview' => true
                )
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        $sOutput = '';
        $sType = $this->getListDisplayType();
        $oViewConfig = Registry::get( 'oxViewConfig' );

        if( !$aParams[ 'articletype' ] )
        {
            if(\OxidEsales\Eshop\Core\Registry::getConfig()->getTopActiveView()->getClassName() == 'start' )
            {
                if( ( $sStartType = $oViewConfig->getViewThemeParam('sStartPageListDisplayType') ) )
                {
                    $sType = $sStartType;
                }
            }

            if( $sType == 'grid' && $oViewConfig->isRoxiveTheme() )
            {
                $sType = 'infogrid';
            }
        }
        else
        {
            $sType = $aParams[ 'articletype' ];
        }

        if( class_exists( ArticleBox::class ) )
        {
            $sOutput .= '<div class="dd-shortcode-' . $this->getShortCode() . ' productData productBox' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">';
            $sOutput .= '[{oxid_include_widget cl="oxwArticleBox" _parent=$oView->getClassName() _navurlparams=$oViewConf->getNavUrlParams() anid="' . $aParams[ 'id' ] . '" isVatIncluded=$oView->isVatIncluded() nocookie=1 sWidgetType=product sListType="listitem_' . $sType . '" inlist=1 skipESIforUser=1}]';
            $sOutput .= '</div>';
        }
        else
        {
            /** @var Article $oArticle */
            $oArticle = oxNew( Article::class );
            $oArticle->load( $aParams[ 'id' ] );

            $oSmarty = Registry::get( 'oxUtilsView' )->getSmarty();
            $oSmarty->assign(
                array(
                    'oView'     => \OxidEsales\Eshop\Core\Registry::getConfig()->getTopActiveView(),
                    'product'   => $oArticle,
                    'type'      => $sType,
                    'css_class' => $aParams[ 'class' ],
                    'shortcode' => $this->getShortCode(),
                )
            );

            $sOutput .= $oSmarty->fetch( 'ddoe_widget_article.tpl' );
        }

        return $sOutput;

    }


    public function searchArticle()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $aArticles = array();

        if( $oConfig->getRequestParameter( 'value' ) )
        {
            /** @var Article $oProduct */
            $oProduct = oxNew( Article::class );
            $oProduct->loadInLang( Registry::getLang()->getEditLanguage(), $oConfig->getRequestParameter( 'value' ) );

            $aArticles[] = array(
                'value'       => $oProduct->getId(),
                'label'       => $oProduct->oxarticles__oxtitle->value,
                'description' => 'Art-Nr.: ' . $oProduct->oxarticles__oxartnum->value . ( $oProduct->oxarticles__oxean->value ? ' / EAN: ' . $oProduct->oxarticles__oxean->value : '' ),
                'icon'        => $oProduct->getIconUrl()
            );

        }
        elseif( $oConfig->getRequestParameter( 'search' ) )
        {
            $oDb = DatabaseProvider::getDb();
            $sSearch = $oConfig->getRequestParameter( 'search' );

            /** @var ArticleList $oList */
            $oList = oxNew( ArticleList::class );
            $sViewName = getViewName( 'oxarticles', Registry::getLang()->getEditLanguage() );

            $sSelect = "SELECT
                            *
                        FROM " . $sViewName . "
                        WHERE (
                            `OXARTNUM` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . " OR
                            `OXTITLE` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . " OR
                            `OXEAN` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . "
                          )
                          AND `OXPARENTID` = ''
                        ";

            $oList->selectString( $sSelect );

            /** @var Article $oProduct */
            foreach( $oList as $oProduct )
            {
                $aArticles[] = array(
                    'value'       => $oProduct->getId(),
                    'label'       => $oProduct->oxarticles__oxtitle->value,
                    'description' => 'Art-Nr.: ' . $oProduct->oxarticles__oxartnum->value . ( $oProduct->oxarticles__oxean->value ? ' / EAN: ' . $oProduct->oxarticles__oxean->value : '' ),
                    'icon'        => $oProduct->getIconUrl()
                );
            }
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $aArticles );
    }

}