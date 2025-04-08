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

class gallery_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_IMAGE_GALLERY';

    protected $_sBackgroundColor = '#00BCD4';

    protected $_sIcon = 'fa-image';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );

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

        $aColumns = array(
            2  => '2 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_RESPONSIVE_COLUMNS' ),
            3  => '3 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_RESPONSIVE_COLUMNS' ),
            4  => '4 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_RESPONSIVE_COLUMNS' ),
            6  => '6 '  . $oLang->translateString( 'DD_VISUAL_EDITOR_RESPONSIVE_COLUMNS' ),
            12 => '12 ' . $oLang->translateString( 'DD_VISUAL_EDITOR_RESPONSIVE_COLUMNS' )
        );

        $this->setOptions(
            array(
                'images'         => array(
                    'type'    => 'image',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGES' ),
                    'multi'   => true,
                    'preview' => true
                ),
                'columns'     => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_RESPONSIVE_COLUMNS' ),
                    'values' => $aColumns,
                    'value'  => 4
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
                        'url'      => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_IMAGE_LINK_TYPE_IMAGE_URL' ),
                    ),
                    'value'  => 'lightbox'
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
        if( !$aParams[ 'images' ] || !is_array( $aParams[ 'images' ] ) )
        {
            return '';
        }

        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $oMedia  = oxNew( Media::class );

        $sGalleryId = 'gallery_' . uniqid();

        $sHTML = '<div class="dd-shortcode-' . $this->getShortCode() . ' dd-image-gallery' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                    <div class="row">';

        foreach( $aParams[ 'images' ] as $sFile )
        {
            $sPath = $oMedia->getMediaPath( $sFile );
            $sURL  = $oMedia->getMediaUrl( $sFile );

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
                else
                {
                    $iThumbSize = '185';
                }

                $sImageURL = $oMedia->getThumbnailUrl( $sFile, $iThumbSize );
            }
            else
            {
                $sImageURL = $sURL;
            }

            if( $oConfig->isSsl() )
            {
                $sURL      = str_replace( $oConfig->getShopUrl(), $oConfig->getSslShopUrl(), $sURL );
                $sImageURL = str_replace( $oConfig->getShopUrl(), $oConfig->getSslShopUrl(), $sImageURL );
            }

            $aImageSize = array( 500, 500 );

            if( function_exists( 'getimagesize' ) )
            {
                if( is_readable( $sPath ) )
                {
                    $aImageSize = getimagesize( $sPath );
                }
                else
                {
                    $aImageSize = getimagesize( $sURL );
                }
            }

            $sHTML .= '<div class="col-xs-12 col-sm-' . ( $aParams[ 'columns' ] ? ( 12/$aParams[ 'columns' ] ) : 3 ) . '">
                           <div class="dd-image-box' . ( $aParams[ 'type' ] == 'lightbox' ? ' dd-image-lightbox' : '' ) . '" data-original-image="' . $sURL . '" data-image-width="' . $aImageSize[ 0 ] . '" data-image-height="' . $aImageSize[ 1 ] . '" data-gallery-id="' . $sGalleryId . '">
                               <a href="' . $sURL . '" target="_blank"' . ( $aParams[ 'show_title' ] ? ' title="' . $sFile . '"' : '' ) . '>
                                   <img '. ( $oConfig->getConfigParam("blEnableLazyLoading") ? 'data-' : '' ) .'src="' . $sImageURL . '" border="0" alt="' . $sFile . '" />
                                   ' . ( $aParams[ 'show_title' ] ? '<span class="dd-image-caption">' . $sFile . '</span>' : '' ) . '
                               </a>
                           </div>
                       </div>';
        }

        $sHTML .= '</div></div>';

        return $sHTML;
    }

}