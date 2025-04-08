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

namespace OxidEsales\VisualCmsModule\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Request;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use OxidEsales\Eshop\Core\Theme;
use OxidEsales\Eshop\Core\UtilsObject;
use OxidEsales\VisualCmsModule\Application\Model\Media;
use OxidEsales\VisualCmsModule\Application\Model\VisualEditor;
use OxidEsales\VisualCmsModule\Application\Model\VisualEditorTemplate;

/**
 * Class VisualCmsAdmin
 */
class VisualCmsAdmin extends AdminDetailsController
{

    protected $_aDynamicBlocks = null;

    protected $_aTreeNodes = array();


    /**
     * @return string
     */
    public function render()
    {
        $this->setEditObjectId( 1 );
        $this->_aViewData[ 'oxid' ] = '1';

        /** @var VisualEditor $oEditor */
        $oEditor = oxNew( VisualEditor::class );

        $this->_aViewData[ 'veditor' ] = $oEditor;
        $this->_aViewData[ 'aCategories' ] = $oEditor->getCategories();

        $oLang = Registry::getLang();
        $this->_aViewData[ 'lang' ] = $oLang->getLanguageNames();
        $this->_aViewData[ 'sActiveLang' ] = $this->_iEditLang;
        $this->_aViewData[ 'blDebugMode' ] = ( Registry::getConfig()->getConfigParam( 'blVisualEditorDebug' ) ? 'true' : 'false' );

        /** @var VisualEditorTemplate $oTmpl */
        $oTmpl = oxNew( VisualEditorTemplate::class );
        $this->_aViewData[ 'templates' ] = $oTmpl->getTemplates();

        $this->_aViewData[ 'aFolder' ] = Registry::getConfig()->getConfigParam( 'aCMSfolder' );
        $this->_aViewData[ 'blocks' ] = array();

        if ( Registry::getConfig()->getConfigParam( 'blEnableVisualEditorBlocks' ) )
        {
            $this->_aViewData[ 'blocks' ] = $this->_getDynamicBlocks();
        }

        $this->_aViewData[ 'demo' ] = $this->isDemoShop();

        $this->_aViewData[ 'sPreloadContentId' ] = Registry::getRequest()->getRequestParameter( 'preloadid' );

        /** @var Content $oDemoContent */
        $oDemoContent = oxNew( Content::class );
        $this->_aViewData[ 'blHasDemoPage' ] = $oDemoContent->loadByIdent( 'oxdemopage' );

        parent::render();

        return "ddoevisualcmsadmin.tpl";
    }


    public function save( $blPreview = false )
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $oLang = Registry::getLang();

        header( 'Content-Type: application/json' );

        if( $this->isDemoShop() && !$blPreview )
        {
            Registry::getUtils()->showJsonAndExit( array( 'error' => true, 'msg' => 'saving not allowed in demo mode' ) );
        }

        $aParams = $oConfig->getRequestParameter( 'editval' );

        if ( $oConfig->getRequestParameter( 'cms_type' ) == 'block' )
        {
            $sId = $oConfig->getRequestParameter( 'oxid' );

            if ( !( $sBlock = $oConfig->getRequestParameter( 'block' ) ) )
            {
                Registry::getUtils()->showJsonAndExit( array( 'error' => true, 'msg' => 'no block selected' ) );
            }

            $sObjectType = ( $aParams[ 'oxcontents__ddobjecttype' ] ? $aParams[ 'oxcontents__ddobjecttype' ] : 'empty' );
            $sLoadId = $sBlock . $sObjectType;

            if ( $sObjectType != 'empty' && ( $sObjectId = $aParams[ 'oxcontents__ddobjectid' ][ $sObjectType ] ) )
            {
                $aParams[ 'oxcontents__ddobjectid' ] = $sObjectId;
                $sLoadId .= $sObjectId;
            }
            else
            {
                $aParams[ 'oxcontents__ddobjectid' ] = '';
            }

            $sLoadId = md5( $sLoadId );

            $aParams[ 'oxcontents__ddobjecttype' ] = $sObjectType;
            $aParams[ 'oxcontents__oxloadid' ] = $sLoadId;
            $aParams[ 'oxcontents__ddblock' ] = $sBlock;
            $aParams[ 'oxcontents__ddisblock' ] = 1;
        }
        else
        {
            $blNew = $oConfig->getRequestParameter( 'new' );
            $sId = $blNew ? null : $oConfig->getRequestParameter( 'oxid' );

            if ( !$sId && !$blNew && !$blPreview )
            {
                Registry::getUtils()->showJsonAndExit( array( 'error' => true, 'msg' => $oLang->translateString( 'DD_VISUAL_EDITOR_NO_ID_GIVEN' ) ) );
            }

            if ( !$aParams[ 'oxcontents__oxloadid' ] )
            {
                $aParams[ 'oxcontents__oxloadid' ] = UtilsObject::getInstance()->generateUId();
            }
            else
            {
                if ( $this->_checkIdent( $aParams[ 'oxcontents__oxloadid' ], $sId ) && !$blPreview )
                {
                    Registry::getUtils()->showJsonAndExit( array( 'error' => true, 'msg' => $oLang->translateString( 'DD_VISUAL_EDITOR_IDENT_ALREADY_IN_USE' ) ) );
                }
            }

            $aParams[ 'oxcontents__ddobjecttype' ] = '';
            $aParams[ 'oxcontents__ddobjectid' ] = '';
            $aParams[ 'oxcontents__ddblock' ] = '';
            $aParams[ 'oxcontents__ddisblock' ] = 0;
        }

        if ( preg_match( "/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $aParams[ 'oxcontents__ddacivefrom' ] ) )
        {
            $aParams[ 'oxcontents__ddacivefrom' ] = substr( $aParams[ 'oxcontents__ddacivefrom' ], 0, 10 ) . ' 00:00:00';
        }
        else
        {
            $aParams[ 'oxcontents__ddacivefrom' ] = '0000-00-00 00:00:00';
        }

        if ( preg_match( "/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $aParams[ 'oxcontents__ddaciveuntil' ] ) )
        {
            $aParams[ 'oxcontents__ddaciveuntil' ] = substr( $aParams[ 'oxcontents__ddaciveuntil' ], 0, 10 ) . ' 00:00:00';
        }
        else
        {
            $aParams[ 'oxcontents__ddaciveuntil' ] = '0000-00-00 00:00:00';
        }

        if ( $aParams[ 'oxcontents__oxtype' ] == 0 )
        {
            $aParams[ 'oxcontents__oxsnippet' ] = 1;
        }
        else
        {
            $aParams[ 'oxcontents__oxsnippet' ] = 0;
        }

        /** @var VisualEditor $oEditor */
        $oEditor = oxNew( VisualEditor::class );

        if ( ( $aWidgets = $oConfig->getRequestParameter( 'widget' ) ) )
        {
            ksort( $aWidgets );

            foreach ( $aWidgets as &$aWidget )
            {
                ksort( $aWidget );
            }

            $aParams[ 'oxcontents__oxcontent' ] = $oEditor->getContent( $aWidgets, $aParams[ 'oxcontents__oxloadid' ], $aParams[ 'oxcontents__ddcssclass' ], $aParams[ 'oxcontents__ddcustomcss' ] );
        }
        elseif ( ( $sContent = $oConfig->getRequestParameter( 'source' ) ) )
        {
            $aParams[ 'oxcontents__oxcontent' ] = $sContent;
        }
        else
        {
            $aParams[ 'oxcontents__oxcontent' ] = '';
        }

        if ( !$oConfig->isUtf() )
        {
            $aParams[ 'oxcontents__oxtitle' ] = utf8_decode( $aParams[ 'oxcontents__oxtitle' ] );
            $aParams[ 'oxcontents__oxcontent' ] = utf8_decode( $aParams[ 'oxcontents__oxcontent' ] );
        }

        /** @var Content $oContent */
        $oContent = oxNew( Content::class );

        if ( $sId )
        {
            $oContent->load( $sId );
        }

        $oContent->setLanguage( 0 );
        $oContent->assign( $aParams );
        // if it is a template then don't save the data by language
        if( !$aParams[ 'oxcontents__ddistmpl' ] )
        {
            $oContent->setLanguage( $this->_iEditLang );
        }

        if ( $blPreview )
        {
            if ( !$sId )
            {
                $sId = md5( $aParams[ 'oxcontents__oxcontent' ] );
            }

            $oSession = Registry::getSession();
            $oSession->setVariable( 'ddpreviewcontent_' . $sId, $aParams );

            $blIsStart = false;

            if ( $oContent->oxcontents__oxloadid->value == 'oxstartwelcome' )
            {
                $blIsStart = true;
            }

            $sUrl = str_replace( 'force_admin_sid', 'force_sid', html_entity_decode( $oConfig->getShopHomeUrl( null, false ) ) );
            $sUrl .= 'cl=ddoevisualcmspreview&id=' . $sId . '&start=' . (int) $blIsStart;

            Registry::getUtils()->showJsonAndExit( array( 'error' => false, 'id' => $sId, 'url' => $sUrl ) );
        }
        else
        {
            $sId = $oContent->save();

            if ( $oConfig->getRequestParameter( 'cms_type' ) == 'block' && $oConfig->getRequestParameter( 'cleartmp' ) )
            {
                $sSmartyDir = Registry::get( 'oxUtilsView' )->getSmartyDir();

                if ( $sSmartyDir && is_readable( $sSmartyDir ) )
                {
                    foreach ( glob( $sSmartyDir . '*' ) as $sFile )
                    {
                        if ( !is_dir( $sFile ) )
                        {
                            @unlink( $sFile );
                        }
                    }
                }

            }
            else
            {
                // Save SEO Data
                $aSeoData = $oConfig->getRequestParameter( 'aSeoData' );
                $this->saveSEO( $sId, $aSeoData, $oContent->getBaseStdLink( $this->_iEditLang, true, false ) );

            }

            Registry::getUtils()->showJsonAndExit( array( 'error' => false, 'id' => $sId, 'new' => (int) $blNew, 'title' => $aParams[ 'oxcontents__oxtitle' ] ) );
        }

    }


    public function savePreview()
    {
        $this->save( true );
    }


    public function saveSEO( $sId, $aSeoData, $sStdUrl = '' )
    {
        if ( !$sId )
        {
            return;
        }

        $iShopId = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();

        /** @var \OxidEsales\Eshop\Application\Model\SeoEncoderContent $oEncoder */
        $oEncoder = Registry::get( "oxSeoEncoderContent" );

        // marking self and page links as expired
        $oEncoder->markAsExpired( $sId, $iShopId, 1, $this->_iEditLang );

        // saving
        $oEncoder->addSeoEntry(
            $sId, $iShopId, $this->_iEditLang, $sStdUrl,
            $aSeoData[ 'oxseourl' ], 'oxcontent', $aSeoData[ 'oxfixed' ],
            trim( $aSeoData[ 'oxkeywords' ] ), trim( $aSeoData[ 'oxdescription' ] )
        );
    }


    public function doShortCodeAction()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        if ( $oConfig->getRequestParameter( 'action' ) && $oConfig->getRequestParameter( 'shortcode' ) )
        {
            /** @var VisualEditor $oEditor */
            $oEditor = oxNew( VisualEditor::class );
            $aShortCodes = $oEditor->getShortCodes();

            return call_user_func( array( $aShortCodes[ $oConfig->getRequestParameter( 'shortcode' ) ], $oConfig->getRequestParameter( 'action' ) ) );
        }

        return false;

    }


    public function searchContents()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $aContents = array();

        if( $oConfig->getRequestParameter( 'all' ) )
        {
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
            $blAllowBlocks = $oConfig->getRequestParameter( 'block' ) == '1';

            $sSelect = "SELECT
                        `OXID`
                    FROM `oxcontents`
                    WHERE `OXSHOPID` = '" . $oConfig->getShopId() . "'
                      AND `DDISTMPL` = 0
                    ";

            if ( !$blAllowBlocks )
            {
                $sSelect .= " AND `DDISBLOCK` = 0 ";
            }

            /** @var Content $oContent */
            foreach ( $oDb->getAll( $sSelect ) as $aRow )
            {
                $aContents[] = $this->_getPreparedContentData( $aRow[ 'OXID' ] );
            }
        }
        elseif ( $oConfig->getRequestParameter( 'search' ) )
        {
            $tvg = oxNew(TableViewNameGenerator::class);
            $contentsView = $tvg->getViewName('oxcontents', $this->_iEditLang);
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );

            $sSearch = $oConfig->getRequestParameter( 'search' );
            $blAllowBlocks = $oConfig->getRequestParameter( 'block' ) == '1';

            $sSelect = "SELECT
                        `OXID`
                    FROM $contentsView
                    WHERE `OXSHOPID` = '" . $oConfig->getShopId() . "'
                      AND `DDISTMPL` = 0
                      AND (
                        `OXLOADID` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . " OR
                        `OXTITLE` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . "
                      )
                    ";

            if ( !$blAllowBlocks )
            {
                $sSelect .= " AND `DDISBLOCK` = 0 ";
            }
            else
            {
                if ( strtolower( $sSearch ) == 'block' )
                {
                    $sSelect .= " OR `DDISBLOCK` = 1 ";
                }
            }

            /** @var Content $oContent */
            foreach ( $oDb->getAll( $sSelect ) as $aRow )
            {
                $aContents[] = $this->_getPreparedContentData( $aRow[ 'OXID' ] );
            }
        }
        elseif( ( $sIdent = $oConfig->getRequestParameter( 'ident' ) ) )
        {
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );

            $sSelect = "SELECT
                          `OXID`
                        FROM `oxcontents`
                        WHERE `OXSHOPID` = '" . $oConfig->getShopId() . "'
                          AND `OXLOADID` = " . $oDb->quote( $sIdent ) . "
                          AND `DDISTMPL` = 0
                        ";

            if( ( $sId = $oDb->getOne( $sSelect ) ) )
            {
                $aContents = array( $this->_getPreparedContentData( $sId ) );
            }
        }
        elseif ( ( $sId = $oConfig->getRequestParameter( 'id' ) ) )
        {
            $aContents = array( $this->_getPreparedContentData( $sId ) );
        }

        if ($oConfig->getRequestParameter( 'definition' )) {
            $aContents['fields_definition'] = $this->getFieldsDefinition();
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $aContents );
    }


    protected function getFieldsDefinition(){
        $res = [
            'id'           => ['id'   => 'oxid'],
            'title'        => [],
            'ident'        => [],
            'cssclass'     => [],
            'active_from'  => ['type' => 'date'],
            'active_until' => ['type' => 'date'],
            'active'       => ['type' => 'checkbox'],
            'hide_title'   => ['type' => 'checkbox'],
            'hide_sidebar' => ['type' => 'checkbox'],
            'fullwidth'    => ['type' => 'checkbox'],
            'islanding'    => ['type' => 'checkbox'],
            'plaintext'    => ['type' => 'checkbox'],
            'folder'       => ['type' => 'select', 'default' => 'CMSFOLDER_NONE'],
            'catid'        => ['type' => 'select']
        ];
        return $res;
    }

    protected function _getPreparedContentData( $sId )
    {
        /** @var \OxidEsales\Eshop\Application\Model\SeoEncoderContent $oEncoder */
        $oEncoder = Registry::get( "oxSeoEncoderContent" );

        $oLang = Registry::getLang();

        /** @var Content $oContent */
        $oContent = oxNew( Content::class );

        $sSelectedLang = $this->_iEditLang;

        $oContent->loadInLang( $sSelectedLang, $sId );

        $iShopId = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId();
        $blNewInLang = false;
        $sFromLang = '';

        $oOtherLang = $oContent->getAvailableInLangs();

        if ( !isset( $oOtherLang[ $sSelectedLang ] ) )
        {
            $sSelectedLang = key( $oOtherLang );
            $blNewInLang = true;
            $sFromLang = $oOtherLang[ $sSelectedLang ];

            $oContent->loadInLang( $sSelectedLang, $sId );
        }

        if( $oContent->oxcontents__ddisblock->value )
        {
            $aDynamicBlocks = $this->_getDynamicBlocks();

            if( $aDynamicBlocks && $aDynamicBlocks[ $oContent->oxcontents__ddblock->value ] )
            {
                $sTitle = 'Block: ' . $aDynamicBlocks[ $oContent->oxcontents__ddblock->value ];
            }
            else
            {
                $sTitle = 'Block: ' . $oContent->oxcontents__ddblock->value;
            }

            switch( $oContent->oxcontents__ddobjecttype->value )
            {
                case 'article':

                    $sArticleNo = DatabaseProvider::getDb()->getOne( "SELECT `OXARTNUM` FROM " . getViewName( 'oxarticles' ) . " WHERE `OXID` = '" . $oContent->oxcontents__ddobjectid->value . "' " );
                    $sDescription = 'Artikel > ' . $sArticleNo;

                    break;

                case 'category':

                    $sCategoryName = DatabaseProvider::getDb()->getOne( "SELECT `OXTITLE` FROM " . getViewName( 'oxcategories' ) . " WHERE `OXID` = '" . $oContent->oxcontents__ddobjectid->value . "' " );
                    $sDescription = 'Kategorie > ' . $sCategoryName;

                    break;

                case 'manufacturer':

                    $sManufacturerName = DatabaseProvider::getDb()->getOne( "SELECT `OXTITLE` FROM " . getViewName( 'oxmanufacturers' ) . " WHERE `OXID` = '" . $oContent->oxcontents__ddobjectid->value . "' " );
                    $sDescription = 'Hersteller > ' . $sManufacturerName;

                    break;

                default:

                    $sDescription = 'Keine Zuordnung';

                    break;
            }

        }
        else
        {
            $aFolder = \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam( 'aCMSfolder' );

            $sTitle = $oContent->oxcontents__oxtitle->value;
            $sDescription = $oContent->oxcontents__oxloadid->value;

            if( $oContent->oxcontents__oxfolder->value && 'CMSFOLDER_NONE' !== $oContent->oxcontents__oxfolder->value )
            {
                $sDescription .= ' (<span style="color: ' . $aFolder[ $oContent->oxcontents__oxfolder->value ] . ';">' . $oLang->translateString( $oContent->oxcontents__oxfolder->value ) . '</span>)';
            }
        }

        // SEO Informations

        $sSelect = "SELECT 
                        `OXFIXED`
                    FROM `oxseo`
                    WHERE `OXOBJECTID` = " . DatabaseProvider::getDb()->quote( $sId ) . "
                      AND `OXSHOPID` = '" . $iShopId . "'
                      AND `OXLANG` = " . ( $sSelectedLang ? $sSelectedLang : '0' ) . "
                      AND `OXPARAMS` = '' ";

        $blURLFixed = (bool) DatabaseProvider::getDb()->getOne( $sSelect, false, false );

        $aSEO = array(
            'fixed'       => $blURLFixed,
            'url'         => $oEncoder->getContentUri( $oContent, $sSelectedLang ),
            'keywords'    => ( ( $sSeoKeywords = $oEncoder->getMetaData( $sId, 'oxkeywords', $iShopId, $sSelectedLang ) ) ? $sSeoKeywords : '' ),
            'description' => ( ( $sSeoDesc = $oEncoder->getMetaData( $sId, 'oxdescription', $iShopId, $sSelectedLang ) ) ? $sSeoDesc : '' ),
        );

        $aData = array(
            'id'           => $oContent->getId(),
            'title'        => html_entity_decode( $sTitle, ENT_QUOTES ),
            'desc'         => $sDescription,
            'ident'        => $oContent->oxcontents__oxloadid->value,
            'fullurl'      => $oContent->getLink(),
            'active'       => $oContent->oxcontents__oxactive->value,
            'active_from'  => substr( $oContent->oxcontents__ddactivefrom->value, 0, 10 ),
            'active_until' => substr( $oContent->oxcontents__ddactiveuntil->value, 0, 10 ),
            'folder'       => $oContent->oxcontents__oxfolder->value,
            'catid'        => $oContent->oxcontents__oxcatid->value,
            'type'         => $oContent->oxcontents__oxtype->value,
            'hide_title'   => $oContent->oxcontents__ddhidetitle->value,
            'hide_sidebar' => $oContent->oxcontents__ddhidesidebar->value,
            'fullwidth'    => $oContent->oxcontents__ddfullwidth->value,
            'islanding'    => $oContent->oxcontents__ddislanding->value,
            'plaintext'    => $oContent->oxcontents__ddplaintext->value,
            'cssclass'     => $oContent->oxcontents__ddcssclass->value,
            'css'          => $oContent->oxcontents__ddcustomcss->rawValue,
            'newinlang'    => $blNewInLang,
            'fromlang'     => $sFromLang,
            'seo'          => $aSEO,
            'url'          => $aSEO[ 'url' ],
            'isactive'     => (int) $oContent->isActive()
        );
        $aData = $this->extendContentData($aData, $oContent);
        return $aData;
    }

    protected function extendContentData($aData, $oContent){
        return $aData;
    }


    protected function _getDynamicBlocks()
    {
        if ( $this->_aDynamicBlocks == null )
        {
            /** @var Theme $oTheme */
            $oTheme = oxNew( Theme::class );
            $this->_aDynamicBlocks = $oTheme->getActiveBlocks();
        }

        return $this->_aDynamicBlocks;
    }


    public function getWidgets()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $iGridsterSize = $oConfig->getConfigParam( 'iGridsterSize' ) ? $oConfig->getConfigParam( 'iGridsterSize' ) : 6;

        $aWidgets = array();

        if ( !( $sContent = $oConfig->getRequestParameter( 'source' ) ) && ( $sId = $oConfig->getRequestParameter( 'id' ) ) )
        {
            /** @var Content $oContent */
            $oContent = oxNew( Content::class );
            //$oContent->load( $oConfig->getRequestParameter( 'id' ) );

            $oContent->loadInLang( $this->_iEditLang, $sId );

            $oOtherLang = $oContent->getAvailableInLangs();
            if ( !isset( $oOtherLang[ $this->_iEditLang ] ) )
            {
                $oContent->loadInLang( key( $oOtherLang ), $sId );
            }

            $sContent = $oContent->oxcontents__oxcontent->value;

        }
        else
        {
            if ( !$oConfig->isUtf() )
            {
                // Convert content back to iso, because we got content in utf8 from editor
                $sContent = utf8_decode( $sContent );
            }
        }

        if ( $sContent )
        {
            /** @var VisualEditor $oEditor */
            $oEditor = oxNew( VisualEditor::class );

            if ( $oEditor->isVisualEditorContent( $sContent ) )
            {
                $aWidgets = $oEditor->getWidgets( $sContent );
            }
            else
            {
                $aWidgets[] = array(
                    'col'           => 1,
                    'row'           => 1,
                    'size_x'        => $iGridsterSize,
                    'size_y'        => 1,
                    'widget'        => 'text',
                    'widget_params' => array(
                        'content' => $oEditor->cleanUpSmartyAttributes( $sContent ),
                    ),
                );
            }
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $aWidgets );
    }


    public function getSource()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sContent = '';

        if ( ( $aWidgets = $oConfig->getRequestParameter( 'widget' ) ) )
        {
            /** @var VisualEditor $oEditor */
            $oEditor = oxNew( VisualEditor::class );

            ksort( $aWidgets );

            foreach ( $aWidgets as &$aWidget )
            {
                ksort( $aWidget );
            }

            $sContent = $oEditor->getContent( $aWidgets );
        }
        elseif ( ( $sId = $oConfig->getRequestParameter( 'id' ) ) )
        {
            /** @var Content $oContent */
            $oContent = oxNew( Content::class );
            //$oContent->load( $oConfig->getRequestParameter( 'id' ) );

            $oContent->loadInLang( $this->_iEditLang, $sId );

            $oOtherLang = $oContent->getAvailableInLangs();
            if ( !isset( $oOtherLang[ $this->_iEditLang ] ) )
            {
                $oContent->loadInLang( key( $oOtherLang ), $sId );
            }

            $sContent = $oContent->oxcontents__oxcontent->rawValue;
        }

        header( 'Content-Type: text/html' );
        Registry::getUtils()->showMessageAndExit( $sContent );
    }


    public function getTemplate()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sContent = '';

        if ( ( $sId = $oConfig->getRequestParameter( 'id' ) ) )
        {
            /** @var VisualEditorTemplate $oTmpl */
            $oTmpl = oxNew( VisualEditorTemplate::class );
            $aTmpl = $oTmpl->getTemplate( $sId );

            $sContent = $aTmpl[ 'OXCONTENT' ];
        }

        header( 'Content-Type: text/html' );
        die( $sContent );
    }


    public function getTemplateData()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $aData = array();

        if ( ( $sId = $oConfig->getRequestParameter( 'id' ) ) )
        {
            /** @var VisualEditorTemplate $oTmpl */
            $oTmpl = oxNew( VisualEditorTemplate::class );
            $aTmpl = $oTmpl->getTemplate( $sId );

            $aData = array(
                'id'          => $aTmpl[ 'OXID' ],
                'title'       => $aTmpl[ 'OXTITLE' ],
                'targetid'    => $aTmpl[ 'DDTMPLTARGETID' ],
                'targetdate'  => $aTmpl[ 'DDTMPLTARGETDATE' ],
                'targettitle' => $aTmpl[ 'DDTMPLTARGETTITLE' ],
                'targetident' => $aTmpl[ 'DDTMPLTARGETIDENT' ],
            );
        }

        header( 'Content-Type: application/json' );
        die( json_encode( $aData ) );
    }


    public function saveTemplateTimer()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $aData = array();

        if ( ( $sId = $oConfig->getRequestParameter( 'oxid' ) ) )
        {
            $aParams = $oConfig->getRequestParameter( 'timer' );

            $sDateDefault = '0000-00-00 00:00:00';

            if ( strlen( $aParams[ 'oxcontents__ddtmpltargetdate' ] ) != strlen( $sDateDefault ) )
            {
                $aParams[ 'oxcontents__ddtmpltargetdate' ] .= substr( $sDateDefault, strlen( $aParams[ 'oxcontents__ddtmpltargetdate' ] ) );
            }

            /** @var Content $oContent */
            $oContent = oxNew( Content::class );
            $oContent->load( $sId );
            $oContent->assign( $aParams );
            $oContent->save();

            /** @var VisualEditorTemplate $oTmpl */
            $oTmpl = oxNew( VisualEditorTemplate::class );
            $aTmpl = $oTmpl->getTemplate( $sId );

            $aData = array(
                'id'          => $aTmpl[ 'OXID' ],
                'title'       => $aTmpl[ 'OXTITLE' ],
                'targetid'    => $aTmpl[ 'DDTMPLTARGETID' ],
                'targetdate'  => $aTmpl[ 'DDTMPLTARGETDATE' ],
                'targettitle' => $aTmpl[ 'DDTMPLTARGETTITLE' ],
                'targetident' => $aTmpl[ 'DDTMPLTARGETIDENT' ],
            );
        }

        header( 'Content-Type: application/json' );
        die( json_encode( array( 'error' => false, 'tmpl' => $aData ) ) );
    }


    protected function _checkIdent( $sIdent, $sId = null )
    {
        $blAllow = false;
        $oDb = DatabaseProvider::getDb();

        $sSelect = "select oxid from oxcontents where oxloadid = " . $oDb->quote( $sIdent ) . " and oxshopid = '" . \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId() . "' ";

        if ( $sId )
        {
            $sSelect .= " and oxid != " . $oDb->quote( $sId );
        }

        // null not allowed
        if ( !strlen( $sIdent ) )
        {
            $blAllow = true;
        }
        elseif ( $oDb->getOne( $sSelect, false, false ) )
        {
            $blAllow = true;
        }

        return $blAllow;
    }


    public function uploadImage()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        /** @var \OxidEsales\Eshop\Core\UtilsFile $oUtils */
        $oUtils = Registry::get( 'oxUtilsFile' );
        $sPath = 'out/pictures/ddvisualeditor';

        if ( $oConfig->getRequestParameter( 'd' ) )
        {
            $sPath .= '/' . $oConfig->getRequestParameter( 'd' );
        }

        $blSuccess = false;
        $sImageUrl = '';

        if ( ( $sImage = $oUtils->processFile( 'file', $sPath ) ) )
        {
            $sImageUrl = $oConfig->getShopUrl( null, false ) . $sPath . '/' . $sImage;
            $blSuccess = true;
        }


        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( array( 'success' => $blSuccess, 'file' => $sImage, 'url' => $sImageUrl ) );
    }


    public function searchObjects()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $aObjects = array();

        if ( $oConfig->getRequestParameter( 'type' ) && $oConfig->getRequestParameter( 'search' ) )
        {
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );

            $sSearch = $oConfig->getRequestParameter( 'search' );
            $sSelect = null;

            switch ( $oConfig->getRequestParameter( 'type' ) )
            {
                case 'article':
                    $sSelect = "SELECT
                                `OXID` AS 'id',
                                `OXTITLE` AS 'title',
                                CONCAT( 'Art-Nr.: ', `OXARTNUM` ) AS 'description'
                            FROM `" . getViewName( 'oxarticles' ) . "`
                            WHERE `OXSHOPID` = '" . $oConfig->getShopId() . "'
                              AND (
                                `OXTITLE` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . " OR
                                `OXARTNUM` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . "
                              )
                            ";
                    break;

                case 'category':
                    $sSelect = "SELECT
                                `OXID` AS 'id',
                                `OXTITLE` AS 'title',
                                `OXDESC` AS 'description'
                            FROM `" . getViewName( 'oxcategories' ) . "`
                            WHERE `OXSHOPID` = '" . $oConfig->getShopId() . "'
                              AND (
                                `OXTITLE` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . "
                              )
                            ";
                    break;

                case 'manufacturer':
                    $sSelect = "SELECT
                                `OXID` AS 'id',
                                `OXTITLE` AS 'title',
                                `OXSHORTDESC` AS 'description'
                            FROM `" . getViewName( 'oxmanufacturers' ) . "`
                            WHERE `OXSHOPID` = '" . $oConfig->getShopId() . "'
                              AND (
                                `OXTITLE` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . "
                              )
                            ";
                    break;
            }

            if( $sSelect )
            {
                foreach ( $oDb->getAll( $sSelect ) as $aRow )
                {
                    $aObjects[] = $aRow;
                }
            }

        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $aObjects );
    }


    public function searchBlock()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $aContent = array();

        if ( $oConfig->getRequestParameter( 'block' ) && $oConfig->getRequestParameter( 'objecttype' ) )
        {
            $sLoadId = $oConfig->getRequestParameter( 'block' ) . $oConfig->getRequestParameter( 'objecttype' );

            if ( $oConfig->getRequestParameter( 'objectid' ) )
            {
                $sLoadId .= $oConfig->getRequestParameter( 'objectid' );
            }

            $sLoadId = md5( $sLoadId );
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );

            $sSelect = "SELECT
                        `OXID`
                    FROM `oxcontents`
                    WHERE `OXSHOPID` = '" . $oConfig->getShopId() . "'
                      AND `DDISBLOCK` = 1
                      AND `OXLOADID` = " . $oDb->quote( $sLoadId ) . "
                    LIMIT 1
                    ";

            $aRow = $oDb->getRow( $sSelect );

            if ( $aRow[ 'OXID' ] )
            {
                $aContent = $this->_getPreparedContentData( $aRow[ 'OXID' ] );
            }
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $aContent );
    }


    public function delete()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        if( $this->isDemoShop() )
        {
            Registry::getUtils()->showJsonAndExit( array( 'error' => true, 'msg' => 'deleting not allowed in demo mode' ) );
        }

        /** @var Content $oContent */
        $oContent = oxNew( Content::class );

        if ( ( $sId = $oConfig->getRequestParameter( 'oxid' ) ) && $oContent->load( $sId ) )
        {
            $oContent->delete();
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( array( 'error' => false ) );
    }


    public function deleteBlock()
    {
        $this->delete();
    }


    public function installDemodata()
    {
        /** @var Content $oDemoContent */
        $oDemoContent = oxNew( Content::class );

        if ( !$oDemoContent->loadByIdent( 'oxdemopage' ) )
        {
            $sDemoSQLPath = $this->getViewConfig()->getModulePath( 'ddoevisualcms', 'demodata/sql/' );

            if ( is_dir( $sDemoSQLPath ) )
            {
                foreach( glob( $sDemoSQLPath . '*.sql' ) as $sSQLPath )
                {
                    try {
                        $sSQL = file_get_contents( $sSQLPath );
                        $sSQL = str_replace( '__SHOP_ID__', \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId(), $sSQL );
                        DatabaseProvider::getDb()->Execute( $sSQL );
                    }
                    catch( Exception $e ) {}
                }
            }

            /** @var Media $oMedia */
            $oMedia = oxNew( Media::class );

            $sMediaPath = $oMedia->getMediaPath();
            $sModuleMediaPath = $this->getViewConfig()->getModulePath( 'ddoevisualcms', 'demodata/ddmedia/' );

            if ( is_dir( $sModuleMediaPath ) )
            {
                $oMedia->createDirs();

                foreach( glob( $sModuleMediaPath . '*.jpg' ) as $sImagePath )
                {
                    copy( $sImagePath, $sMediaPath . basename( $sImagePath ) );
                }

                foreach ( glob( $sModuleMediaPath . 'thumbs/*.jpg' ) as $sImagePath )
                {
                    copy( $sImagePath, $sMediaPath . basename( $sImagePath ) );
                }
            }

        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( array( 'success' => true ) );
    }


    /**
     * AJAX Getter
     * Returns a JSON encoded array of nodes for a treeview
     */
    public function getTreeviewNodes()
    {
        $oRequest = Registry::getRequest();
        $sParentOxid = $oRequest->getRequestParameter( 'id' );
        $bSnippets = ( $oRequest->getRequestParameter( 'type' ) == 'main' ) ? false : true;
        $iEditLang = $oRequest->getRequestParameter( 'editlanguage' );

        if( !array_key_exists( $sParentOxid, $this->_aTreeNodes ) )
        {
            /** @var Content $oContent */
            $oContent = oxNew( Content::class );
            $this->_aTreeNodes[ $sParentOxid ] = $oContent->getTreeviewNodes( $sParentOxid, $bSnippets, $iEditLang );
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $this->_aTreeNodes[ $sParentOxid ] );
    }


    /**
     * AJAX Setter
     * Saves hierarchy and position of nodes in a treeview
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function saveTreeviewNode()
    {
        /** @var Request $oRequest */
        $oRequest = Registry::getRequest();
        /** @var Config $oConfig */
        $oConfig = Registry::getConfig();
        $oDb = DatabaseProvider::getDb();
        $sNodeOxid = $oRequest->getRequestParameter( 'id' );
        $sParentOxid = $oRequest->getRequestParameter( 'parentid' );
        $sNodePosition = $oRequest->getRequestParameter( 'pos' );
        $sOldParentOxid = $oRequest->getRequestParameter( 'oldparentid' );
        $sOldNodePosition = $oRequest->getRequestParameter( 'oldpos' );
        $bError = false;
        $sError = '';

        try
        {
            // Update old neighbor nodes if any
            if( !empty($sOldParentOxid) && $sOldParentOxid != '#')
            {
                $oDb->execute(
                    "UPDATE `oxcontents`
                    SET `DDSORTING` = `DDSORTING` - 1
                    WHERE ( `OXSHOPID` = ? OR `OXSHOPID` = '' ) AND `DDPARENTCONTENT` = ? AND `DDSORTING` > ?",
                    array(
                        $oConfig->getShopId(),
                        $sOldParentOxid,
                        $sOldNodePosition,
                    )
                );
            }

            // Update new neighbor nodes
            $oDb->execute(
                "UPDATE `oxcontents`
                    SET `DDSORTING` = `DDSORTING` + 1
                    WHERE ( `OXSHOPID` = ? OR `OXSHOPID` = '' ) AND `DDPARENTCONTENT` = ? AND `DDSORTING` >= ?",
                array(
                    $oConfig->getShopId(),
                    $sParentOxid,
                    $sNodePosition,
                )
            );

            // Update current node
            $oDb->execute(
                "UPDATE `oxcontents`
                    SET `DDPARENTCONTENT` = ?, `DDSORTING` = ? 
                    WHERE ( `OXSHOPID` = ? OR `OXSHOPID` = '' ) AND `OXID` = ?",
                array(
                    $sParentOxid,
                    $sNodePosition,
                    $oConfig->getShopId(),
                    $sNodeOxid,
                )
            );

            // Regenerate SEO-URL
            /** @var Content $oContent */
            $oContent = oxNew( Content::class );
            $oContent->load( $sNodeOxid );
            /** @var \OxidEsales\Eshop\Application\Model\SeoEncoderContent $oEncoder */
            $oEncoder = Registry::get( "oxSeoEncoderContent" );
            $iShopId = Registry::getConfig()->getShopId();

            $blURLFixed = (bool) $oDb->getOne(
                "SELECT 
                        `OXFIXED`
                    FROM `oxseo`
                    WHERE `OXOBJECTID` = ?
                      AND `OXSHOPID` = ?
                      AND `OXLANG` = ?
                      AND `OXPARAMS` = '' ",
                array(
                    $sNodeOxid,
                    $iShopId,
                    $this->_iEditLang,
                )
            );

            $aSEO = array(
                'oxfixed'       => $blURLFixed,
                'oxseourl'      => $oEncoder->getTreeviewContentUri( $oContent, $this->_iEditLang, true ),
                'oxkeywords'    => ( ( $sSeoKeywords = $oEncoder->getMetaData( $sNodeOxid, 'oxkeywords', $iShopId, $this->_iEditLang ) ) ? $sSeoKeywords : '' ),
                'oxdescription' => ( ( $sSeoDesc = $oEncoder->getMetaData( $sNodeOxid, 'oxdescription', $iShopId, $this->_iEditLang ) ) ? $sSeoDesc : '' ),
            );

            $this->saveSEO( $sNodeOxid, $aSEO, $oContent->getBaseStdLink( $this->_iEditLang, true, false ) );
        }
        catch ( \Exception $ex )
        {
            $bError = true;
            $sError = $ex->getMessage();
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit(
            array(
                'Error'        => $bError,
                'ErrorMessage' => $sError,
            )
        );
    }


    /**
     * AJAX Getter
     * Searches for a CMS-content page in the database and
     * returns a JSON encoded array of node OXIDs that need
     * to be opened in order to show the results
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function searchTreeviewNodes()
    {
        /** @var Request $oRequest */
        $oRequest = Registry::getRequest();
        /** @var Content $oContent */
        $oContent = oxNew( Content::class );
        /** @var Config $oConfig */
        $oConfig = Registry::getConfig();
        $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
        $sQuery = '%' . $oRequest->getRequestParameter( 'str' ) . '%';
        $bSnippets = ( $oRequest->getRequestParameter( 'type' ) == 'main' ) ? false : true;
        $iEditLang = $oRequest->getRequestParameter( 'editlanguage' );
        $aSearchResult = array();

        $sSql = "SELECT `OXID`
                 FROM `oxcontents`
                 WHERE ( `OXSHOPID` = ? OR `OXSHOPID` = '' )";

        $sSql .= ( !$bSnippets ) ? " AND `OXTYPE` != 0" : " AND `OXTYPE` = 0";

        $sSql .=" AND ( `OXTITLE` LIKE ? OR `OXLOADID` LIKE ? )";

        $aRes = $oDb->getAll(
            $sSql,
            array(
                $oConfig->getShopId(),
                $sQuery,
                $sQuery,
            )
        );

        foreach( $aRes as $aRow )
        {
            $aNodeList = $oContent->getTreeviewRootNodeList( $aRow[ 'OXID' ], $iEditLang )['aNodeList'];
            foreach( $aNodeList as $sNode )
            {
                $aSearchResult[] = $sNode;
            }
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( array_unique( $aSearchResult ) );
    }


    /**
     * Returns a string that contains the active admin language and the version number of visualCMS
     * module for appending to the visualCMS help page link. Default is "en/3.3" if no version is set.
     * @return string Active adminlanguage abbreviation and version number without patch-part
     */
    public function getVisualCmsHelpVersion()
    {
        $sLang = 'en';
        $sVersion = '3.3';
        $oModule = oxNew(\OxidEsales\Eshop\Core\Module\Module::class);

        if ($oModule->load('ddoevisualcms')) {
            $sModuleVersion = $oModule->getInfo("version");
            $sVersion = $sModuleVersion ? $this->_removePatchVersionNumber($sModuleVersion) : $sVersion;
        }

        // German URL if adminlanguage is German
        /** @var Language $oLang */
        $oLang = Registry::getLang();

        foreach ($oLang->getLanguageArray() as $langitem) {

            if ($langitem->selected && $langitem->id === $this->_aViewData[ 'adminlang' ] && $langitem->abbr === 'de') {
                $sLang = 'de';
                break;
            }
        }

        return $sLang . '/' . $sVersion;
    }


    /**
     * Removes last part of version number string
     * @param $sVersion module version number (e.g. 3.3.3)
     * @return string returns version number without patch version number (e.g. 3.3)
     */
    protected function _removePatchVersionNumber($sModuleVersion)
    {
        return substr($sModuleVersion, 0, strpos($sModuleVersion, '.', strpos($sModuleVersion, '.') +1));
    }
}
