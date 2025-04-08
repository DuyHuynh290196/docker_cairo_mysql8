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

use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Module\ModuleList;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class VisualEditor
 *
 * @mixin \OxidEsales\Eshop\Core\Model\BaseModel
 */
class VisualEditor extends BaseModel
{
    protected $_aShortCodes = array();

    protected $_aGridNumbers = array(
        1  => 'one',
        2  => 'two',
        3  => 'three',
        4  => 'four',
        5  => 'five',
        6  => 'six',
        7  => 'seven',
        8  => 'eight',
        9  => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
    );

    // Tmp properties for grid layout
    protected $_iGridSize = null;
    protected $_sRowClass = null;
    protected $_sColClassPrefix = null;
    protected $_sOffsetClassPrefix = null;

    // Ressources after parsing
    protected $_aStyles = array();
    protected $_aScripts = array();

    protected $_aCategoryCachePath = [];
    protected $_aCategories = [];


    public function __construct()
    {
        parent::__construct();
    }


    public function getShortCodes( $blWidgets = true )
    {
        if( !$this->_aShortCodes )
        {
            $aPath = array();
            $aShortcodes = array();

            $aPath[] = realpath( __DIR__ . DIRECTORY_SEPARATOR . '../../Core/shortcodes' );

            /** @var ModuleList $oModules */
            $oModules = oxNew( ModuleList::class );
            foreach( $oModules->getActiveModuleInfo() as $sModuleDir )
            {
                $sModuleShortcodePath = getShopBasePath() . 'modules/' . $sModuleDir . '/visualcms/shortcodes';

                if( is_readable( $sModuleShortcodePath ) )
                {
                    $aPath[] = $sModuleShortcodePath;
                }

                // add compatibility to digidesk visual editor widgets
                $sModuleShortcodePath = getShopBasePath() . 'modules/' . $sModuleDir . '/dd_visual_editor/shortcodes';

                if( is_readable( $sModuleShortcodePath ) )
                {
                    $aPath[] = $sModuleShortcodePath;
                }
            }



            foreach( $aPath as $sPath )
            {
                foreach( new \DirectoryIterator( $sPath ) as $oFile )
                {
                    if( $oFile->isDir() || $oFile->isDot() )
                    {
                        continue;
                    }

                    $aShortcodes[ $oFile->getBasename( '.php' ) . '_shortcode' ] =  $oFile->getPathname();
                }
            }

            ksort( $aShortcodes );

            foreach( $aShortcodes as $sShortcodeClass => $sShortcodePath )
            {
                include_once $sShortcodePath;

                /** @var VisualEditorShortcode $oShortCode */
                $oShortCode = new $sShortcodeClass( $this );
                //$oShortCode->setEditor( $this );

                if( ( $blWidgets && $oShortCode->isWidget() ) || !$blWidgets )
                {
                    $this->_aShortCodes[ $oShortCode->getShortCode() ] = $oShortCode;
                }
            }

        }

        return $this->_aShortCodes;

    }


    public function parse( $sContent = '', $blContainer = true, $sCssClass = '', $sCustomCss = '' )
    {
        $this->_parseGridLayout( $sContent, $blContainer, $sCssClass );
        $this->_parseWidgets( $sContent );

        if( $sCustomCss )
        {
            $this->_addCustomCss( $sContent, $sCustomCss );
        }

        $this->_addRessources( $sContent );

        return $sContent;
    }


    public function getContent( $aWidgets, $sContentId = '', $sCssClass = '', $sCustomCss = '' )
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $iGridSize     = $oConfig->getConfigParam( 'iGridSize' ) ? $oConfig->getConfigParam( 'iGridSize' ) : 12;
        $iGridsterSize = $oConfig->getConfigParam( 'iGridsterSize' ) ? $oConfig->getConfigParam( 'iGridsterSize' ) : 6;
        $iColumnMultiplier = floor( $iGridSize / $iGridsterSize );

        // Parse CSS/LESS through Less.php
        if( $sCustomCss )
        {
            require_once getShopBasePath() . 'modules/ddoe/visualcms/libs/less.php/Less.php';

            $oLessParser = new \Less_Parser( array( 'compress' => true ) );
            $oLessParser->parse( $sCustomCss );
            $sCustomCss = $oLessParser->getCss();
        }

        $html = '[{veparse' . ( $sContentId ? ' name="' . $sContentId . '"' : '' ) . ( $sCssClass ? ' cssclass="' . $sCssClass . '"' : '' ) . ( $sCustomCss ? ' css="' . addslashes( $sCustomCss ) . '"' : '' ) . '}]';

        // Widget content
        $html .= $this->_getWidgetsContent( $aWidgets, $iColumnMultiplier );

        $html .= '[{/veparse}]';

        return $html;
    }


    public function getWidgets( $sContent = '', $blNested = false )
    {
        $data = array();

        $aShortCodes = $this->getShortCodes();

        // Prüfen, ob der String aus der DB UTF-8 kodiert ist, sonst ein Encoding durchführen
        if ( !Registry::getUtils()->isUtfString( $sContent ) )
        {
            $sContent = utf8_encode( $sContent );
        }

        preg_match_all( "/" . $this->_getShortCodeRegex( ( $blNested ? 'n' : '' ) . 'row' ) . "/", $sContent, $row_matches );

        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $iGridSize = $oConfig->getConfigParam( 'iGridSize' ) ? $oConfig->getConfigParam( 'iGridSize' ) : 12;
        $iGridsterSize = $oConfig->getConfigParam( 'iGridsterSize' ) ? $oConfig->getConfigParam( 'iGridsterSize' ) : 6;
        $iColumnDivider = floor( $iGridSize / $iGridsterSize );

        $r = 1;

        foreach( $row_matches[ 5 ] as $row => $content )
        {
            $h = 1;
            $c = 1;

            preg_match_all( "/" . $this->_getShortCodeRegex( ( $blNested ? 'n' : '' ) . 'col' ) . "/", $content, $col_matches );

            foreach( $col_matches[ 5 ] as $col => $content )
            {
                $aColumnOptions = $this->_parseShortCodeAttributes( $col_matches[ 3 ][ $col ] );

                preg_match_all( "/" . $this->_getShortCodeRegex() . "/", $content, $object_matches );

                foreach( $object_matches[ 5 ] as $object => $content )
                {
                    $shortcode = $object_matches[ 2 ][ $object ];
                    $args = $this->_parseShortCodeAttributes( $object_matches[ 3 ][ $object ] );

                    if( $args )
                    {
                        $opts = $aShortCodes[ $shortcode ]->getOptions();

                        foreach( $args as $key => $val )
                        {
                            if( $opts[ $key ]  )
                            {
                                if( ( $opts[ $key ][ 'type' ] == 'multi' || ( $opts[ $key ][ 'type' ] == 'image' && $opts[ $key ][ 'multi' ] ) ) )
                                {
                                    $args[ $key ] = explode( '|', $val );
                                }

                                if( $opts[ $key ][ 'type' ] == 'wysiwyg' )
                                {
                                    $args[ $key ] = $this->cleanUpSmartyAttributes( $val );
                                }
                            }

                        }
                    }

                    $aChildren = array();

                    if( $shortcode == 'column' )
                    {
                        $aChildren = $this->getWidgets( $content, true );
                    }
                    elseif( $content )
                    {
                        $args[ 'content' ] = $this->cleanUpSmartyAttributes( $content );
                    }

                    //$args = $aShortCodes[ $object_matches[ 1 ][ $object ] ]->getParams( $args );

                    if( $aColumnOptions[ 'class' ] && !$oConfig->getConfigParam( 'blCustomGridFramework' ) )
                    {
                        $args[ 'col_class' ] = explode( ' ', $aColumnOptions[ 'class' ] );
                    }

                    $c += ( $aColumnOptions[ 'offset' ]/$iColumnDivider );

                    $data[] = array(
                        'col' => $c,
                        'row' => $r,
                        'size_x' => $aColumnOptions[ 'size' ]/$iColumnDivider,
                        'size_y' => ( $aColumnOptions[ 'height' ] ? $aColumnOptions[ 'height' ] : 1 ),
                        'widget' => $shortcode,
                        'widget_params' => $args,
                        'children' => $aChildren
                    );

                    $c += ( $aColumnOptions[ 'size' ]/$iColumnDivider );

                    if( $aColumnOptions[ 'height' ] > 1 && $aColumnOptions[ 'height' ] > $h )
                    {
                        $h = $aColumnOptions[ 'height' ];
                    }

                    if( $c >= $iGridsterSize && $h > 1 )
                    {
                        $r += ( $h - 1 );
                    }

                }

                //$c++;

            }

            $r++;

        }

        return $data;

    }


    public function isVisualEditorContent( $sContent )
    {
        return (bool) preg_match( "/\[\{veparse/", $sContent );
    }


    public function addInlineScript( $sScript, $blCheckExists = false, $blSmarty = true )
    {
        $this->_aScripts[ ( $blCheckExists ? md5( $sScript ) : uniqid() ) ] = array(
            'url'     => null,
            'check'   => $blCheckExists,
            'inline'  => $sScript,
            'smarty'  => $blSmarty
        );
    }


    public function addScript( $sScript, $blCheckExists = false, $blSmarty = true )
    {
        $this->_aScripts[ ( $blCheckExists ? basename( $sScript ) : uniqid() ) ] = array(
            'url'     => $sScript,
            'inline'  => null,
            'check'   => $blCheckExists,
            'smarty'  => $blSmarty
        );
    }


    public function addInlineStyle( $sStyle, $blCheckExists = false )
    {
        $this->_aStyles[ ( $blCheckExists ? md5( $sStyle ) : uniqid() ) ] = array(
            'url'     => null,
            'check'   => $blCheckExists,
            'inline'  => $sStyle,
            'smarty'  => false
        );
    }


    public function addStyle( $sStyle, $blCheckExists = false, $blSmarty = true )
    {
        $this->_aStyles[ ( $blCheckExists ? basename( $sStyle ) : uniqid() ) ] = array(
            'url'     => $sStyle,
            'inline'  => null,
            'check'   => $blCheckExists,
            'smarty'  => $blSmarty
        );
    }


    public function getCategories( $iLang = null )
    {
        if( $iLang === null )
        {
            $iLang = 0;
        }

        if( !isset( $this->_aCategories[ $iLang ] ) || $this->_aCategories[ $iLang ] == null )
        {
            $this->_aCategories[ $iLang ] = null;

            $oDB = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );

            if( is_readable( $this->_getCategoryCachePath( $iLang ) ) )
            {
                $iLatestCategoryChange = strtotime( $oDB->getOne( "SELECT `OXTIMESTAMP` FROM `oxcategories` ORDER BY `OXTIMESTAMP` DESC LIMIT 1" ) );
                $iFileTimestamp        = filemtime( $this->_getCategoryCachePath( $iLang ) );

                if( $iLatestCategoryChange < $iFileTimestamp )
                {
                    $this->_aCategories[ $iLang ] = json_decode( file_get_contents( $this->_getCategoryCachePath( $iLang ) ), true );
                }
            }

            // Prüfen, ob der Cache neu erstellt werden muss
            if( true || $this->_aCategories[ $iLang ] === null )
            {
                $sCategoriesView = getViewName( 'oxcategories', $iLang );
                $aCategories = $oDB->getAll( "SELECT `OXID`, `OXPARENTID`, `OXTITLE` FROM `{$sCategoriesView}` WHERE `OXACTIVE` = 1" );

                $this->_aCategories[ $iLang ] = $this->_buildCategories( $aCategories, $iLang );

                file_put_contents( $this->_getCategoryCachePath( $iLang ), json_encode( $this->_aCategories[ $iLang ] ) );
            }
        }

        return $this->_aCategories[ $iLang ];

    }


    protected function _getWidgetsContent( $aWidgets, $iColumnMultiplier, $blNested = false )
    {
        $html = '';

        foreach( $aWidgets as $iRow => $aCols )
        {
            $html .= '[' . ( $blNested ? 'n' : '' ) . 'row]';

            $c = 1;

            foreach( $aCols as $iCol => $sData )
            {
                $aData = ( is_string( $sData ) ? json_decode( $sData ) : $sData );

                if( $iCol > $c )
                {
                    $iOffset = $iCol - $c;
                    $c = $iCol;
                }
                else
                {
                    $iOffset = 0;
                }

                $sColClass = '';

                if( $aData->widget_params && isset( $aData->widget_params->col_class ) )
                {
                    if( !empty( $aData->widget_params->col_class ) )
                    {
                        $sColClass = ( is_array( $aData->widget_params->col_class ) ? implode( ' ', $aData->widget_params->col_class ) : $aData->widget_params->col_class );
                    }

                    unset( $aData->widget_params->col_class );
                }

                $html .= '[' . ( $blNested ? 'n' : '' ) . 'col size="' . ($aData->size_x*$iColumnMultiplier) . '"' . ( $aData->size_y > 1 ? ' height="' . $aData->size_y . '"' : '' ) . ' offset="' . ($iOffset*$iColumnMultiplier) . '" class="' . $sColClass . '"]';

                if( !empty( $aData->widget_params->fullwidth ) || !empty( $aData->widget_params->background_color ) || !empty( $aData->widget_params->background_image ) )
                {
                    $html .= '[background' . ( $aData->widget_params->fullwidth ? ' fullwidth="1"' : '' ) . ( $aData->widget_params->background_color ? ' color="' . $aData->widget_params->background_color . '"' : '' ) . ( $aData->widget_params->background_image ? ' image="' . $aData->widget_params->background_image . '"' . ( $aData->widget_params->background_fixed ? ' fixed="1"' : '' ) : '' ) . ']';
                }

                $html .= '[' . $aData->widget;

                $sContent = '';

                if( $aData->widget_params )
                {
                    foreach( $aData->widget_params as $sKey => $sValue )
                    {
                        if( $sKey == 'content' )
                        {
                            $sContent = $this->_stripShortCodes( $sValue );
                        }
                        else
                        {
                            $html .= ' ' . $sKey . '="' . htmlentities( ( is_array( $sValue ) ? implode( '|', $sValue ) : $sValue ) ) . '"';
                        }
                    }
                }

                $html .= ']';

                if( $aData->widget == 'column' && $aData->children )
                {
                    $aChildWidgets = array();

                    foreach( $aData->children as $_aChildData )
                    {
                        $aChildWidgets[ $_aChildData->row ][ $_aChildData->col ] = $_aChildData;
                    }

                    $html .= $this->_getWidgetsContent( $aChildWidgets, $iColumnMultiplier, true );
                }
                elseif( $sContent )
                {
                    $html .= str_replace( "-&gt;", '->', $sContent );
                }

                $html .= '[/' . $aData->widget . ']';

                if( !empty( $aData->widget_params->fullwidth ) || !empty( $aData->widget_params->background_color ) || !empty( $aData->widget_params->background_image ) )
                {
                    $html .= '[/background]';
                }

                $html .= '[/' . ( $blNested ? 'n' : '' ) . 'col]';

                $c += $aData->size_x;

            }

            $html .= '[/' . ( $blNested ? 'n' : '' ) . 'row]';

        }

        return $html;
    }


    protected function _parseGridLayout( &$sContent, $blContainer = true, $sCssClass = '' )
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $this->_iGridSize = $oConfig->getConfigParam( 'iGridSize' ) ? $oConfig->getConfigParam( 'iGridSize' ) : 12;

        $this->_sRowClass          = 'row';
        $this->_sColClassPrefix    = 'col-sm-';
        $this->_sOffsetClassPrefix = 'col-sm-offset-';

        if( $oConfig->getConfigParam( 'blCustomGridFramework' ) )
        {
            if( $oConfig->getConfigParam( 'sGridColPrefix' ) )
            {
                $this->_sColClassPrefix = $oConfig->getConfigParam( 'sGridColPrefix' );
            }

            if( $oConfig->getConfigParam( 'sGridOffsetPrefix' ) )
            {
                $this->_sOffsetClassPrefix = $oConfig->getConfigParam( 'sGridOffsetPrefix' );
            }

            if( $oConfig->getConfigParam( 'sGridRow' ) )
            {
                $this->_sRowClass = $oConfig->getConfigParam( 'sGridRow' );
            }
        }

        $sContent = preg_replace( "/\[(row|nrow)\]/i", '<div class="' . $this->_sRowClass . '">', $sContent );
        //$sContent = preg_replace( "/\[(col|ncol) size=\"([0-9]+)\"( height=\"([0-9]+)\")?( offset=\"([0-9]+)\")?( class=\"([^\"]*)\")?\]/i", '<div class="' . $sColClassPrefix . '$2 ' . $sOffsetClassPrefix . '$5 $7">', $sContent );
        $sContent = preg_replace_callback( "/\[(col|ncol) size=\"([0-9]+)\"( height=\"([0-9]+)\")?( offset=\"([0-9]+)\")?( class=\"([^\"]*)\")?\]/i", array( $this, '_parseColumnCallback' ), $sContent );

        //$sContent = str_replace( $sOffsetClassPrefix . '0', '', $sContent );

        if( $oConfig->getConfigParam( 'blCustomGridFramework' ) && $oConfig->getConfigParam( 'blGridWordNumbers' ) )
        {
            $aSearchCols = array();
            $aReplaceCols = array();

            for( $i = 1; $i <= $this->_iGridSize; $i++ )
            {
                $aSearchCols[]  = $this->_sColClassPrefix . $i;
                $aSearchCols[]  = $this->_sOffsetClassPrefix . $i;
                $aReplaceCols[] = $this->_sColClassPrefix . $this->_aGridNumbers[ $i ];
                $aReplaceCols[] = $this->_sOffsetClassPrefix . $this->_aGridNumbers[ $i ];
            }

            $sContent = str_replace( $aSearchCols, $aReplaceCols, $sContent );
        }

        $sContent = preg_replace( "/\[\/(row|col|nrow|ncol)\]/i", '</div>', $sContent );

        if( $blContainer )
        {
            $sContent = '<div class="container-fluid dd-ve-container clearfix' . ( $sCssClass ? ' ' . $sCssClass : '' ) . '">' . $sContent . '</div>';
        }

    }

    protected function _parseWidgets( &$sContent )
    {
        if ( false === strpos( $sContent, '[' ) )
        {
            return;
        }

        $aShortCodes = $this->getShortCodes( false );

        if( empty( $aShortCodes ) )
        {
            return;
        }

        // Find all registered tag names in $content.
        preg_match_all( '@\[([^<>&/\[\]\{\}\x00-\x20=]++)@', $sContent, $matches );
        $aTagNames = array_intersect( array_keys( $aShortCodes ), $matches[ 1 ] );

        if ( empty( $aTagNames ) )
        {
            return;
        }

        $sShortCodeRegex = $this->_getShortCodeRegex( $aTagNames );
        $sContent = preg_replace_callback( "/" . $sShortCodeRegex . "/", array( $this, '_parseWidgetCallback'), $sContent );

        /*foreach( $this->getShortCodes( false ) as $sShortCode => $oObject )
        {
            $regex = '#\[(' . $sShortCode . ')([^\]]*)]((?:[^[]|\[(?!/?(' . $sShortCode . ')])|(?R))*)\[/(' . $sShortCode . ')]#';
            $sContent = preg_replace_callback( $regex, array( $this, '_parseWidgetCallback'), $sContent );

        }*/
    }

    protected function _parseWidgetCallback( $content )
    {
        if( is_array( $content ) )
        {
            $shortcode = $content[ 2 ];
            $args = array();

            if( $content[ 3 ] )
            {
                $args = $this->_parseShortCodeAttributes( $content[ 3 ] );

                if( $args )
                {
                    $opts = $this->_aShortCodes[ $shortcode ]->getOptions();

                    foreach( $args as $key => $val )
                    {
                        if( $opts[ $key ] && ( $opts[ $key ][ 'type' ] == 'multi' || ( $opts[ $key ][ 'type' ] == 'image' && $opts[ $key ][ 'multi' ] ) ) )
                        {
                            $args[ $key ] = explode( '|', $val );
                        }
                    }
                }
            }

            $this->_aShortCodes[ $shortcode ]->setParams( $args );
            $content = $this->_aShortCodes[ $shortcode ]->parse( $content[ 5 ], $args );
        }

        return $content;

    }


    protected function _parseColumnCallback( $matches )
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $sGridColClasses = ( $matches[ 8 ] && !$oConfig->getConfigParam( 'blCustomGridFramework' ) ? ' ' . $matches[ 8 ] : '' );
        return '<div class="' . $this->_sColClassPrefix . $matches[ 2 ] . ( $matches[ 6 ] ? ' ' . $this->_sOffsetClassPrefix . $matches[ 6 ] : '' ) . $sGridColClasses . '">';
    }


    protected function _addCustomCss( &$sContent, $sCustomCss = '' )
    {
        $sContent = '<style type="text/css">' . stripslashes( $sCustomCss ) . '</style>' . $sContent;
    }


    protected function _addRessources( &$sContent )
    {
        $_sNewContent = '';

        if( $this->_aStyles )
        {
            $_sInlineStyles = '';

            foreach( $this->_aStyles as $aStyle )
            {
                if( $aStyle[ 'url' ] )
                {
                    if( $aStyle[ 'smarty' ] )
                    {
                        $_sNewContent .= '[{oxstyle include="' . $aStyle[ 'url' ] . '"}] ';
                    }
                    else
                    {
                        $_sInlineStyles .= '@import url("' . $aStyle[ 'url' ] . '"); ';
                    }
                }
                elseif( $aStyle[ 'inline' ] )
                {
                    $_sInlineStyles = $aStyle[ 'inline' ] . ' ';
                }
            }

            if( $_sInlineStyles )
            {
                $_sNewContent .= '<style type="text/css">' . $_sInlineStyles . '</style>';
            }
        }

        $_sNewContent .= $sContent;

        if( $this->_aScripts )
        {
            foreach( $this->_aScripts as $aScript )
            {
                if( $aScript[ 'smarty' ] )
                {
                    if( $aScript[ 'url' ] )
                    {
                        $_sNewContent .= '[{oxscript include="' . $aScript[ 'url' ] . '" priority=20}] ';
                    }
                    elseif( $aScript[ 'inline' ] )
                    {
                        $_sNewContent .= '[{oxscript add="' . $aScript[ 'inline' ] . '" priority=20}] ';
                    }
                }
                else
                {
                    if( $aScript[ 'url' ] )
                    {
                        $_sNewContent .= '<script type="text/javascript" src="' . $aScript[ 'url' ] . '"></script>';
                    }
                    elseif( $aScript[ 'inline' ] )
                    {
                        $_sNewContent .= '<script type="text/javascript">' . $aScript[ 'inline' ] . '</script>';
                    }
                }

            }
        }

        $sContent = $_sNewContent;
    }


    protected function _getShortCodeRegex( $aTagNames = null )
    {
        if ( empty( $aTagNames ) )
        {
            $aShortCodes = $this->getShortCodes();
            $aTagNames = array_keys( $aShortCodes );
        }

        if( !is_array( $aTagNames ) )
        {
            $sTagRegex = $aTagNames;
        }
        else
        {
            $sTagRegex = join( '|', array_map( 'preg_quote', $aTagNames ) );
        }

        return
            '\\['                              // Opening bracket
            . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
            . "($sTagRegex)"                     // 2: Shortcode name
            . '(?![\\w-])'                       // Not followed by word character or hyphen
            . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
            .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
            .     '(?:'
            .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
            .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
            .     ')*?'
            . ')'
            . '(?:'
            .     '(\\/)'                        // 4: Self closing tag ...
            .     '\\]'                          // ... and closing bracket
            . '|'
            .     '\\]'                          // Closing bracket
            .     '(?:'
            .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
            .             '[^\\[]*+'             // Not an opening bracket
            .             '(?:'
            .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
            .                 '[^\\[]*+'         // Not an opening bracket
            .             ')*+'
            .         ')'
            .         '\\[\\/\\2\\]'             // Closing shortcode tag
            .     ')?'
            . ')'
            . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }


    protected function _getShortCodeAttributesRegex()
    {
        return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)/';
    }


    protected function _parseShortCodeAttributes( $sAttributes = '' )
    {
        $sAttributes = trim( $sAttributes );
        $sAttributesRegex = $this->_getShortCodeAttributesRegex();

        $aAttrs = array();

        if( preg_match_all( $sAttributesRegex, $sAttributes, $matches ) )
        {
            foreach( $matches[ 0 ] as $i => $sAttribute )
            {
                if( $matches[ 1 ][ $i ] )
                {
                    $aAttrs[ $matches[ 1 ][ $i ] ] = html_entity_decode( $matches[ 2 ][ $i ] );
                }
            }
        }

        return $aAttrs;

    }


    protected function _stripShortCodes( $sContent = '' ) {

        if ( false === strpos( $sContent, '[' ) )
        {
            return $sContent;
        }

        $aShortCodes = $this->getShortCodes();

        if ( empty( $aShortCodes ) )
        {
            return $sContent;
        }

        // Find all registered tag names in $content.
        preg_match_all( '@\[([^<>&/\[\]\{\}\x00-\x20=]++)@', $sContent, $matches );
        $aTagNames = array_intersect( array_keys( $aShortCodes ), $matches[ 1 ] );

        if ( empty( $aTagNames ) )
        {
            return $sContent;
        }

        $sShortCodeRegex = $this->_getShortCodeRegex( $aTagNames );
        $sContent = preg_replace_callback( "/" . $sShortCodeRegex . "/", array( $this, '_stripShortCodeCallback' ), $sContent );

        return $sContent;
    }


    protected function _stripShortCodeCallback( $content )
    {
        return $content[1] . $content[6];
    }


    protected function _buildCategories( $aCats, $iLang = null, &$aData = array(), $iLevel = 0 )
    {
        if( $iLang === null )
        {
            $iLang = 0;
        }

        if( $aCats )
        {
            foreach( $aCats as $aCat )
            {
                $aData[ $aCat[ 'OXID' ] ] = $this->_getBreadCrumb( $aCat[ 'OXID' ], $iLang );
            }

            asort( $aData );
        }

        return $aData;

    }

    /**
     * Returns category breadcrumb
     *
     * @param string $sOXID
     * @param string $sBreadCrumb
     *
     * @return string
     */
    protected function _getBreadCrumb( $sOXID, $iLang = null, $sBreadCrumb = '' )
    {
        if( $iLang === null )
        {
            $iLang = 0;
        }

        $sCategoriesTable = getViewName( 'oxcategories', $iLang );

        $aCat = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC )->getRow( "SELECT `OXPARENTID`, `OXTITLE` FROM `{$sCategoriesTable}` WHERE `OXID` = '{$sOXID}'" );

        if( !is_array( $aCat ) || count( $aCat ) <= 0 )
        {
            return $sBreadCrumb;
        }

        if( !empty( $sBreadCrumb ) )
        {
            $sBreadCrumb = ' > ' . $sBreadCrumb;
        }

        $sBreadCrumb = $aCat['OXTITLE'] . $sBreadCrumb;

        if( $aCat[ 'OXPARENTID' ] != 'oxrootid' )
        {
            $sBreadCrumb = $this->_getBreadCrumb( $aCat[ 'OXPARENTID' ], $iLang, $sBreadCrumb );
        }

        return $sBreadCrumb;
    }

    /**
     * returns the cache path for the categorie cache
     *
     * @return string
     */
    protected function _getCategoryCachePath( $iLang = null )
    {
        if( $iLang === null )
        {
            $iLang = 0;
        }

        if ( !isset( $this->_aCategoryCachePath[ $iLang ] ) || $this->_aCategoryCachePath[ $iLang ] === null )
        {
            /** @var oxConfig $oConfig */
            $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

            if ( $oConfig )
            {
                $sShopId = $oConfig->getShopId();
                $this->_aCategoryCachePath[ $iLang ] = $oConfig->getConfigParam( 'sCompileDir' ) . DIRECTORY_SEPARATOR . $sShopId . '_' . $iLang . '_dd_category_cache.json';
            }
        }

        return $this->_aCategoryCachePath[ $iLang ];
    }


    public function cleanUpSmartyAttributes( $sContent )
    {
        $sContent = preg_replace_callback( "/(=\s*\")([^>]*)(\")/i", array( $this, '_checkSmartyDoubleQuoteSnippets' ),  $sContent );
        //$sContent = preg_replace_callback( "/(=\s*')([^>]*)(')/i", array( $this, '_checkSmartySingleQuoteSnippets' ),  $sContent );

        return $sContent;
    }

    protected function _checkSmartyDoubleQuoteSnippets( $matches )
    {
        $matches[ 2 ] = preg_replace_callback( "/\[\{[^\[\{]*\}\]/", array( $this, '_replaceSmartyDoubleQuotes' ), $matches[ 2 ] );

        return $matches[ 1 ] . $matches[ 2 ] . $matches[ 3 ];
    }

    protected function _checkSmartySingleQuoteSnippets( $matches )
    {
        $matches[ 1 ] = str_replace( '\'', '"', $matches[ 1 ] );
        $matches[ 2 ] = preg_replace_callback( "/\[\{[^\[\{]*\}\]/", array( $this, '_replaceSmartyDoubleQuotes' ), $matches[ 2 ] );
        $matches[ 3 ] = str_replace( '\'', '"', $matches[ 3 ] );

        return $matches[ 1 ] . $matches[ 2 ] . $matches[ 3 ];
    }

    protected function _replaceSmartyDoubleQuotes( $matches )
    {
        return str_replace( '"', '\'', $matches[ 0 ] );
    }

}
