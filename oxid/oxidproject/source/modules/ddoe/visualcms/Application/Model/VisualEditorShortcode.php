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

namespace OxidEsales\VisualCmsModule\Application\Model;

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class VisualEditorShortcode
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\FrontendController
 */
class VisualEditorShortcode extends FrontendController
{

    /**
     * @var VisualEditor
     */
    protected $_oEditor = null;

    protected $_aOptions = array();

    protected $_sTitle = '';

    protected $_sBackgroundColor = '';

    protected $_sIcon = '';

    protected $_sShortCode = '';

    protected $_blIsWidget = true;

    protected $_aCategories = [];

    protected $_aParams = null;


    public function __construct( $oEditor = null )
    {
        if( $oEditor )
        {
            $this->setEditor( $oEditor );
        }

        parent::__construct();

        $this->install();

        $config = Registry::getConfig();
        if ($config->isAdmin()){
            $this->setInterfaceOptions();
        }
    }


    public function install()
    {
    }


    public function setInterfaceOptions()
    {
    }


    public function setEditor( $oObject )
    {
        $this->_oEditor = $oObject;
    }


    public function getEditor()
    {
        return $this->_oEditor;
    }


    public function setShortCode( $sCode = '' )
    {
        $this->_sShortCode = $sCode;
    }


    public function getShortCode()
    {
        return $this->_sShortCode;
    }


    public function setOptions( $aOptions = array() )
    {
        $this->_aOptions = $aOptions;
    }


    public function getOptions()
    {
        return $this->_aOptions;
    }


    public function isWidget()
    {
        return $this->_blIsWidget;
    }


    public function getPreviewOption()
    {
        foreach( $this->_aOptions as $name => $opt )
        {
            if( $opt[ 'preview' ] )
            {
                return $name;
            }
        }

        return false;
    }


    public function addDefaultOptions()
    {

    }


    public function setTitle( $sTitle = '' )
    {
        $this->_sTitle = $sTitle;
    }


    public function getTitle()
    {
        return Registry::getLang()->translateString( $this->_sTitle, null, true );
    }


    public function setBackgroundColor( $sColor = '' )
    {
        $this->_sBackgroundColor = $sColor;
    }


    public function getBackgroundColor()
    {
        return $this->_sBackgroundColor;
    }


    public function setIcon( $sIcon = '' )
    {
        $this->_sIcon = $sIcon;
    }


    public function getIcon()
    {
        return $this->_sIcon;
    }


    public function addInlineScript( $sScript, $blCheckExists = false, $blSmarty = true )
    {
        if( $this->_oEditor )
        {
            $this->_oEditor->addInlineScript( $sScript, $blCheckExists, $blSmarty );
        }
    }


    public function addScript( $sScript, $blCheckExists = false, $blSmarty = true )
    {
        if( $this->_oEditor )
        {
            $this->_oEditor->addScript( $sScript, $blCheckExists, $blSmarty );
    }
    }


    public function addInlineStyle( $sStyle, $blCheckExists = false )
    {
        if( $this->_oEditor )
        {
            $this->_oEditor->addInlineStyle( $sStyle, $blCheckExists );
        }
    }


    public function addStyle( $sStyle, $blCheckExists = false, $blSmarty = true )
    {
        if( $this->_oEditor )
        {
            $this->_oEditor->addStyle( $sStyle, $blCheckExists, $blSmarty );
        }
    }


    public function getParams()
    {
        return $this->_aParams;
    }


    public function setParams( $aParams = array() )
    {
        $this->_aParams = $aParams;
    }


    public function setData( $aData = array(), $sHash = '' )
    {
        if( !$sHash && $this->_aParams == null )
        {
            return;
        }

        if( !$sHash )
        {
            $sHash = md5( implode( '|', $this->_aParams ) );
        }

        $sVarName = substr( 'ddve_' . $this->_sShortCode . '_' . $sHash, 0, 32 );

        \OxidEsales\Eshop\Core\Registry::getConfig()->saveShopConfVar( 'string', $sVarName, json_encode( $aData ), \OxidEsales\Eshop\Core\Registry::getConfig()->getActiveShop()->getId(), 'module:ddoevisualcms' );
    }


    public function getData( $sHash = '' )
    {
        if( !$sHash && $this->_aParams == null )
        {
            return array();
        }

        if( !$sHash )
        {
            $sHash = md5( implode( '|', $this->_aParams ) );
        }

        $sVarName = substr( 'ddve_' . $this->_sShortCode . '_' . $sHash, 0, 32 );

        $sDataString = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopConfVar( $sVarName, \OxidEsales\Eshop\Core\Registry::getConfig()->getActiveShop()->getId(), 'module:ddoevisualcms' );

        if( $sDataString )
        {
            return (array) json_decode( $sDataString );
        }
        else
        {
            return array();
        }
    }


    public function parse( $sContent = '', $aParams = array() )
    {
        return '<div class="dd-shortcode-' . $this->getShortCode() . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">' . $sContent . '</div>';
    }


    public function getCategories( $iLang = null )
    {
        if( $iLang === null )
        {
            $iLang = 0;
        }

        if( ( !isset( $this->_aCategories[ $iLang ] ) || $this->_aCategories[ $iLang ] == null ) && $this->_oEditor )
        {
            $this->_aCategories[ $iLang ] = $this->_oEditor->getCategories( $iLang );
        }

        return $this->_aCategories[ $iLang ];
    }


}
