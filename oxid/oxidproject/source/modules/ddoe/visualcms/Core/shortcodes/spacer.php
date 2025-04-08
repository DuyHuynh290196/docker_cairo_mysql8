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

class spacer_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_SPACER';

    protected $_sBackgroundColor = '#95a5a6';

    protected $_sIcon = 'fa-minus';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $this->setOptions(
            array(
                'space' => array(
                    'type'        => 'text',
                    'label'       => Registry::getLang()->translateString( 'DD_VISUAL_EDITOR_WIDGET_SPACE' ),
                    'placeholder' => '20',
                    'preview'     => true
                )
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        return '<div class="clearfix dd-shortcode-' . $this->getShortCode() . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '" style="height: ' . ( $aParams[ 'space' ] ? (int) $aParams[ 'space' ] : '20' ) . 'px;"></div>';
    }

}