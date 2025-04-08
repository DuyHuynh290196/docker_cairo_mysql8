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

class icon_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_ICON';

    protected $_sBackgroundColor = '#8e44ad';

    protected $_sIcon = 'fa-star';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'icon'     => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ICON' ),
                    'values' => ( $this->isAdmin() ? $this->_getIcons() : array() ),
                    'value'  => 'fa-star'
                ),
                'position' => array(
                    'type'   => 'select',
                    'label'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ICON_POS' ),
                    'values' => array(
                        'top'  => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ICON_POS_TOP' ),
                        'left' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ICON_POS_LEFT' ),
                    ),
                    'value'  => 'top'
                ),
                'color'    => array(
                    'type'  => 'color',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ICON_COLOR' ),
                    'value' => '#333'
                ),
                'circle'   => array(
                    'type'  => 'checkbox',
                    'label' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_ICON_CIRCLE' )
                ),
                'content'  => array(
                    'type'    => 'wysiwyg',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_CONTENT' ),
                    'preview' => true
                )
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        return '<div class="dd-shortcode-' . $this->getShortCode() . ' dd-icon-box' . ( $aParams[ 'position' ] && $aParams[ 'position' ] == 'left' ? ' dd-icon-box-left' : '' ) . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                    <div class="dd-icon' . ( $aParams[ 'circle' ] ? ' dd-circle' : '' ) . '"' . ( $aParams[ 'circle' ] && $aParams[ 'color' ] ? ' style="background-color: ' . $aParams[ 'color' ] . ';"' : '' ) . '>
                        <i class="fa fa-3x ' . $aParams[ 'icon' ] . '"' . ( $aParams[ 'color' ] && !$aParams[ 'circle' ] ? ' style="color: ' . $aParams[ 'color' ] . ';"' : '' ) . '></i>
                    </div>
                    <div class="dd-icon-text">
                        ' . $sContent . '
                    </div>
                </div>';
    }


    protected function _getIcons()
    {
        /** @var $aIcons array */
        include_once dirname( __FILE__ ) . '/inc/icons.php';

        $aOptions = array();

        foreach( $aIcons as $sIcon )
        {
            $aOptions[ $sIcon ] = '<i class="fa ' . $sIcon . '"></i>' . str_replace( 'fa-', '', $sIcon );
        }

        return $aOptions;
    }

}