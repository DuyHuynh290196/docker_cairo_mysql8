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
use OxidEsales\VisualCmsModule\Application\Model\Media;

use OxidEsales\Eshop\Core\Registry;

class carousel_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_CAROUSEL';

    protected $_sBackgroundColor = '#34495e';

    protected $_sIcon = 'fa-circle-o';

    public function install()
    {
        $this->setShortCode(basename(__FILE__, '.php'));

        // set critical frontend options
        $this->setOptions(
            [
                'images' => [
                    'type'    => 'image',
                    'multi'   => true,
                ]
            ]
        );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'images'         => array(
                    'type'    => 'image',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGES' ),
                    'multi'   => true,
                    'preview' => true
                ),
                'interval'         => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CAROUSEL_INTERVAL' ),
                    'values' => array(
                        2000  => 2  . ' ' . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_SECONDS' ),
                        4000  => 4  . ' ' . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_SECONDS' ),
                        6000  => 6  . ' ' . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_SECONDS' ),
                        8000  => 8  . ' ' . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_SECONDS' ),
                        10000 => 10 . ' ' . $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_SECONDS' ),
                    ),
                ),
                'arrows' => array(
                    'type'   => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CAROUSEL_ENABLE_ARROWS' ),
                ),
                'bullets' => array(
                    'type'   => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CAROUSEL_ENABLE_BULLETS' ),
                ),
                'fullwidth'        => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_FULLWIDTH' )
                )
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        if( !$aParams[ 'images' ] || !is_array( $aParams[ 'images' ] ) )
        {
            return '';
        }

        $this->addInlineStyle( "
            .carousel-control > .fa {
                top: 50%;
                position: absolute;
                transform: translateY(-50%);
                font-size: 2em;
            }
        ", true );


        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $oMedia  = oxNew( Media::class );

        $sCarouselId = 'dd_carousel_' . md5( uniqid() );

        $sHTML = '<div class="dd-shortcode-' . $this->getShortCode() . ' dd-carousel-slider' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                    <div id="' . $sCarouselId . '" class="carousel slide" data-ride="carousel"' . ( $aParams[ 'interval' ] ? ' data-interval="' . $aParams[ 'interval' ] . '"' : '' ) . '>
                    
                        <div class="carousel-inner" role="listbox">';

        $blFirst = true;
        $sBulletsHTML = '';

        foreach( $aParams[ 'images' ] as $i => $sFile )
        {
            $sURL  = $oMedia->getMediaUrl( $sFile );

            if( $oConfig->isSsl() )
            {
                $sURL = str_replace( $oConfig->getShopUrl(), $oConfig->getSslShopUrl(), $sURL );
            }

            $sHTML .= '<div class="item' . ( $blFirst ? ' active' : '' ) . '">
                           <img src="' . $sURL . '" border="0" />
                       </div>';

            if( $aParams[ 'bullets' ] )
            {
                $sBulletsHTML .= '<li data-target="#' . $sCarouselId . '" data-slide-to="' . $i . '"' . ( $blFirst ? ' class="active"' : '' ) . '></li>';
            }

            if( $blFirst )
            {
                $blFirst = false;
            }
        }

        if( $aParams[ 'bullets' ] )
        {
            $sHTML .= '<ol class="carousel-indicators">' . $sBulletsHTML . '</ol>';
        }

        if( $aParams[ 'arrows' ] )
        {
            $sHTML .= '<a class="left carousel-control" href="#' . $sCarouselId . '" role="button" data-slide="prev">
                         <i class="icon-angle-left fa fa-angle-left" aria-hidden="true"></i>
                         <span class="sr-only">Previous</span>
                       </a>
                       
                       <a class="right carousel-control" href="#' . $sCarouselId . '" role="button" data-slide="next">
                         <i class="icon-angle-right fa fa-angle-right" aria-hidden="true"></i>
                         <span class="sr-only">Next</span>
                       </a>';
        }

        $sHTML .= '</div></div></div>';

        return $sHTML;
    }

}