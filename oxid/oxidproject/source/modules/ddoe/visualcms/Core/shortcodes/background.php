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

class background_shortcode extends VisualEditorShortcode
{
    protected $_blIsWidget = false;

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }


    public function parse( $sContent = '', $aParams = array() )
    {
        $style = '';

        if( $aParams[ 'color' ] )
        {
            $style .= 'background-color: ' . $aParams[ 'color' ] . '; ';
        }

        if( $aParams[ 'image' ] )
        {
            $oMedia = oxNew( Media::class );

            $style .= 'background-image: url( \'' . $oMedia->getMediaUrl( $aParams[ 'image' ] ) . '\' ); ';
        }

        $sContent = $this->getEditor()->parse( $sContent, false );

        return '<div class="dd-background' . ( $aParams[ 'fullwidth' ] ? ' dd-fullwidth' : '' ) . ( $aParams[ 'fixed' ] ? ' dd-background-fixed' : '' ) . '" style="' . $style . '">' . $sContent . '</div>';
    }
}
