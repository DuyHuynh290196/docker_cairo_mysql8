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

class image_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_IMAGE';

    protected $_sBackgroundColor = '#C93C57';

    protected $_sIcon = 'fa-image';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'image'          => array(
                    'type'    => 'image',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGES' ),
                    'preview' => true
                ),
                'title'          => array(
                    'type'  => 'text',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_TITLE' ),
                ),
                'show_title'     => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_SHOW_TITLE' )
                ),
                'type'           => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_LINK_TYPE' ),
                    'values' => array(
                        'lightbox' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_LINK_TYPE_LIGHTBOX' ),
                        'url'      => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_LINK_TYPE_URL' ),
                        'none'     => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_LINK_TYPE_NONE' ),
                    ),
                    'value'  => 'lightbox'
                ),
                'url'            => array(
                    'type'  => 'text',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_LINK_TYPE_URL' )
                ),
                'url_blank'      => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_URL_NEW_WINDOW' )
                ),
                'thumbnail'      => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_USE_THUMBNAIL' )
                ),
                'thumbnail_size' => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_THUMBNAIL_SIZES' ),
                    'values' => array(
                        'small'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_THUMBNAIL_SIZES_SMALL' ),
                        'medium' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_THUMBNAIL_SIZES_MEDIUM' ),
                        'large'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_THUMBNAIL_SIZES_LARGE' ),
                    ),
                    'value'  => 'small'
                ),
                'fullwidth'      => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_FULLWIDTH' )
                )
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $iThumbSize = '185';

        if( $aParams[ 'thumbnail' ] )
        {
            if( $aParams[ 'thumbnail_size' ] == 'medium' )
            {
                $iThumbSize = '300';
            }
            elseif( $aParams[ 'thumbnail_size' ] == 'large' )
            {
                $iThumbSize = '800';
            }
        }

        $oMedia  = oxNew( Media::class );

        $sPath     = ( $aParams[ 'image' ]     ? $oMedia->getMediaPath( $aParams[ 'image' ] ) : '' );
        $sURL      = ( $aParams[ 'image' ]     ? $oMedia->getMediaUrl( $aParams[ 'image' ] ) : '' );
        $sThumbURL = ( $aParams[ 'thumbnail' ] ? $oMedia->getThumbnailUrl( $aParams[ 'image' ], $iThumbSize ) : '' );

        $aImageSize = array( 0, 0 );

        if( $sPath && function_exists( 'getimagesize' ) )
        {
            $aImageSize = getimagesize( $sPath );
        }

        if( $oConfig->isSsl() )
        {
            if( $aParams[ 'url' ] )
            {
                $aParams[ 'url' ] = str_replace( $oConfig->getShopUrl(), $oConfig->getSslShopUrl(), $aParams[ 'url' ] );
            }

            if( $aParams[ 'thumbnail' ] )
            {
                $sThumbURL = str_replace( $oConfig->getShopUrl(), $oConfig->getSslShopUrl(), $sThumbURL );
            }

            $sURL = str_replace( $oConfig->getShopUrl(), $oConfig->getSslShopUrl(), $sURL );
        }

        if( $aParams[ 'type' ] == 'url' && $aParams[ 'url'] )
        {
            $sLink = $aParams[ 'url'];
        }
        else
        {
            $sLink = $sPath;
        }

        return '<div class="dd-shortcode-' . $this->getShortCode() . ' dd-image-box' . ( $aParams[ 'type' ] == 'lightbox' ? ' dd-image-lightbox' : '' ) . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '" data-original-image="' . $sURL . '" data-image-width="' . $aImageSize[ 0 ] . '" data-image-height="' . $aImageSize[ 1 ] . '">
                    <' . ( $aParams[ 'type' ] == 'none' ? 'span' : 'a href="' . $sLink . '"' . ( $aParams[ 'type' ] == 'url' && $aParams[ 'url_blank'] ? ' target="_blank"' : '' ) ) . ( $aParams[ 'title' ] ? ' title="' . $aParams[ 'title' ] . '"' : '' ) . '>
                        <img '. ( $oConfig->getConfigParam("blEnableLazyLoading") ? 'data-' : '' ) .'src="' . ( $aParams[ 'thumbnail' ] ? $sThumbURL : $sURL ) . '" border="0"' . ( $aParams[ 'title' ] ? ' alt="' . $aParams[ 'title' ] . '"' : 'alt=" "' ) . ' />
                        ' . ( $aParams[ 'show_title' ] && $aParams[ 'title' ] ? '<span class="dd-image-caption">' . $aParams[ 'title' ] . '</span>' : '' ) . '
                    </' . ( $aParams[ 'type' ] == 'none' ? 'span' : 'a' ) . '>
                </div>';
    }

}
