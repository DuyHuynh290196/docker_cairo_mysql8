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

class hero_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_HERO';

    protected $_sBackgroundColor = '#7BB58E';

    protected $_sIcon = 'fa-rocket';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'headline'         => array(
                    'type'    => 'text',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_HERO_HEADLINE' ),
                    'preview' => true
                ),
                'content'          => array(
                    'type'  => 'wysiwyg',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CONTENT' )
                ),
                'color'            => array(
                    'type'  => 'color',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_HERO_TEXT_COLOR' )
                ),
                'background_color' => array(
                    'type'  => 'color',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_BG_COLOR' )
                ),
                'background_image' => array(
                    'type'  => 'file',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_BG_IMAGE' )
                ),
                'background_fixed' => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_BG_FIXED' )
                ),
                'fixed_height'     => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_HERO_FIXED' )
                ),
                'cta'              => array(
                    'type'  => 'text',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_HERO_CALL_TO_ACTION' )
                ),
                'cta_url'          => array(
                    'type'  => 'text',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_HERO_CALL_TO_ACTION_URL' )
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
        return '<div class="dd-shortcode-' . $this->getShortCode() . ' dd-hero-box' . ( $aParams[ 'fixed_height' ] ? ' dd-hero-fixed' : '' ) . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                    <div class="dd-hero-inner">
                        <div class="dd-hero-holder"' . ( $aParams[ 'color' ] ? ' style="color: ' . $aParams[ 'color' ] . ';"' : '' ) . '>
                            ' . ( $aParams[ 'headline' ] ? '<h1>' . $aParams[ 'headline' ] . '</h1>' : '' ). '
                            ' . $sContent . '
                            ' . ( $aParams[ 'cta'] && $aParams['cta_url' ] ? '<a href="' . $aParams['cta_url' ] . '" class="btn btn-lg btn-link"' . ( $aParams[ 'color' ] ? ' style="color: ' . $aParams[ 'color' ] . '; border-color: ' . $aParams[ 'color' ] . ';"' : '' ) . '>' . $aParams[ 'cta' ] . '</a>' : '' ). '
                        </div>
                    </div>
                </div>';
    }

}