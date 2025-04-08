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

class randomarticle_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_RANDOM_ARTICLE';

    protected $_sBackgroundColor = '#EF6C00';

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
                'categoryid'   => array(
                    'type'        => 'select',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CATEGORY' ),
                    'placeholder' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CHOOSE_CATEGORY' ),
                    'values'      => $this->getCategories( $oLang->getEditLanguage() ),
                    'dataFields'  => array(
                        'name' => 'label'
                    )

                ),
                'ttl'      => array(
                    'type'        => 'text',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_DISPLAY_DURATION' ),
                    'help'        => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_DISPLAY_DURATION_HELP' ),
                    'placeholder' => 0
                ),
                'minstock' => array(
                    'type'        => 'text',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_MINSTOCK' ),
                ),
                'minprice' => array(
                    'type'        => 'text',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_MINPRICE' ),
                ),
                'maxprice' => array(
                    'type'        => 'text',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_MAXPRICE' ),
                ),
                'onlysale' => array(
                    'type'        => 'checkbox',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ONLYSALE' ),
                    'value'       => 1
                ),
                'fallback' => array(
                    'type'        => 'checkbox',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_RANDOM_SHOP_ARTICLE' ),
                    'value'       => 1
                ),
                'uniqueid' => array(
                    'type'        => 'hidden',
                    'random'      => true // generates a random value (number with 10 digits)
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
        $sArticleId = $this->_getArticleId( $aParams );
        $sOutput    = '';
        $sType      = $this->getListDisplayType();

        $oViewConfig = Registry::get( 'oxViewConfig' );

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

        if( $sArticleId )
        {
            if( class_exists( ArticleBox::class ) )
            {
                $sOutput .= '<div class="dd-shortcode-' . $this->getShortCode() . ' productData productBox' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">';
                $sOutput .= '[{oxid_include_widget cl="oxwArticleBox" _parent=$oView->getClassName() _navurlparams=$oViewConf->getNavUrlParams()  anid="' . $sArticleId . '" isVatIncluded=$oView->isVatIncluded() nocookie=1 sWidgetType=product sListType="listitem_' . $sType . '" inlist=1 skipESIforUser=1}]';
                $sOutput .= '</div>';
            }
            else
            {
                $oArticle = oxNew( Article::class );
                $oArticle->load( $sArticleId );

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

                $sOutput .= $oSmarty->fetch( 'widget/ve/article.tpl' );
            }
        }

        return $sOutput;

    }


    protected function _getArticleId( $aParams = array() )
    {
        if( !$aParams )
        {
            return null;
        }

        $aData = $this->getData();
        $sArticleId = null;

        if( (int)$aParams[ 'ttl' ] > 0 && $aData[ 'articleid' ] && $aData[ 'valid_until' ] > time() )
        {
            $sArticleId = $aData[ 'articleid' ];
        }
        else
        {
            $sArticleId = $this->_findArticle( $aParams );

            $this->setData(
                array(
                    'articleid' => $sArticleId,
                    'valid_until' => time() + ( (int)$aParams[ 'ttl'] * 60 )
                )
            );
        }

        return $sArticleId;
    }


    protected function _findArticle( $aParams )
    {
        $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );

        // Artikel mit gesetztem Flag aus den angegebenen Kategorien raussuchen
        $sSQL = "SELECT
                     oa.oxid,
                     oa.oxstock,
                     oa.oxvarstock
                 FROM " . getViewName( 'oxarticles' ) . " AS oa
                     INNER JOIN oxobject2category AS o2c ON o2c.oxobjectid = oa.oxid
                     INNER JOIN oxcategories AS oc ON oc.oxid = o2c.oxcatnid
                 WHERE oa.oxactive = 1";

        // Kategorie
        if( $aParams[ 'categoryid' ] )
        {
            $sSQL .= " AND oc.oxid = " . $oDb->quote( $aParams[ 'categoryid' ] );
        }

        // Mindestpreis
        if ( $aParams[ 'minprice' ] )
        {
            $sSQL .= " AND oa.oxprice >= " . $oDb->quote( (int)$aParams[ 'minprice' ] );
        }

        // Maximalpreis
        if ( $aParams[ 'maxprice' ] )
        {
            $sSQL .= " AND oa.oxprice <= " . $oDb->quote( (int)$aParams[ 'maxprice' ] );
        }

        // Nur reduzierte Artikel
        if ( $aParams[ 'onlysale' ] )
        {
            $sSQL .= " AND ( oa.oxtprice > 0 AND oa.oxtprice > oa.oxprice )";
        }

        $sSQL .= " GROUP BY oa.oxid";

        $aAll = $oDb->getAll( $sSQL );

        // Überprüfen, ob für den Artikel der Mindestbestand erreicht wird
        // Bei Vaterartikeln muss die Summe der Lagermengen der Varianten berücksichtigt werden
        $aSelected = Array();

        foreach ( $aAll AS $aArticle )
        {
            if ( $aArticle[ 'OXSTOCK' ] + $aArticle[ 'OXVARSTOCK' ] > (int)$aParams[ 'minstock' ] )
            {
                $aSelected[] = $aArticle[ 'OXID' ];
            }
        }

        // Wenn kein Artikel gefunden wurde, dann einen zufälligen Artikel aus allen Shopkategorien suchen, für den das Flag gesetzt ist und bei dem die Mindestmenge erreicht wird
        if ( empty( $aSelected ) && $aParams[ 'fallback' ] )
        {
            $sSQL = "SELECT
                         oa.oxid,
                         oa.oxstock,
                         oa.oxvarstock
                     FROM " . getViewName( 'oxarticles' ) . " AS oa
                     WHERE oa.oxactive = 1
                     GROUP BY oa.oxid";

            $aAll = $oDb->getAll( $sSQL );

            // Überprüfen, ob für den Artikel der Mindestbestand erreicht wird
            // Bei Vaterartikeln muss die Summe der Lagermengen der Varianten berücksichtigt werden
            $aSelected = Array();

            foreach ( $aAll AS $aArticle )
            {
                if ( $aArticle[ 'OXSTOCK' ] + $aArticle[ 'OXVARSTOCK' ] > (int)$aParams[ 'minstock' ] )
                {
                    $aSelected[] = $aArticle[ 'OXID' ];
                }
            }

            // Wenn dann auch kein Artikel gefunden wird, dann einen zufälligen Artikel aus allen Shopkategorien anzeigen, damit die Box nicht leer bleibt
            if( empty( $aSelected ) )
            {
                foreach ( $aAll AS $aArticle )
                {
                    $aSelected[] = $aArticle[ 'OXID' ];
                }
            }
        }

        // Aus den ermittelten Artikeln zufällig einen wählen
        $sArticleId =  $aSelected[ rand( 0, count( $aSelected )-1 )];

        return $sArticleId;
    }


}
