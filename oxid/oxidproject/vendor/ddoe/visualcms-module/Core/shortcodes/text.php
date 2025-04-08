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

class text_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_TEXT';

    protected $_sBackgroundColor = '#3498db';

    protected $_sIcon = 'fa-align-left';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'content'          => array(
                    'type'    => 'wysiwyg',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CONTENT' ),
                    'preview' => true
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
                'fullwidth'        => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_FULLWIDTH' )
                )
            )
        );
    }
}