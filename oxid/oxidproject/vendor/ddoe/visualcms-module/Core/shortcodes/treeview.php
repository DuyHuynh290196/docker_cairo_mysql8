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
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\Eshop\Application\Model\ContentList;

class treeview_shortcode extends VisualEditorShortcode
{

    protected $_sTitle = 'DD_VISUAL_EDITOR_SHORTCODE_TREEVIEW';

    protected $_sBackgroundColor = '#3498db';

    protected $_sIcon = 'fa-sitemap';

    public function install()
    {
        $this->setShortCode( basename( __FILE__, '.php' ) );
    }

    public function setInterfaceOptions()
    {
        $oLang = Registry::getLang();

        $this->setOptions(
            array(
                'id' => array(
                    'data'        => 'searchAction',
                    'type'        => 'select',
                    'label'       => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TREEVIEW_CONTENT' ),
                    'placeholder' => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TREEVIEW_CHOOSE_CONTENT' ),
                    'dataFields'  => array(
                        'name' => 'label'
                    )

                ),
                'title'      => array(
                    'type'    => 'text',
                    'label'   => $oLang->translateString( 'DD_VISUAL_EDITOR_WIDGET_TREEVIEW_TITLE' )
                ),
                'name'      => array(
                    'type'    => 'hidden',
                    'preview' => true
                ),
            )
        );
    }

    public function parse( $sContent = '', $aParams = array() )
    {
        /** @var Content $oContent */
        $oContent      = oxNew( Content::class );
        $sContent     = '';
        if( empty( $aParams[ 'title' ] ) )
        {
            $aParams[ 'title' ] = Registry::getLang()->translateString( 'DD_VISUAL_EDITOR_WIDGET_TREEVIEW_DEFAULT_TITLE' );
        }

        if( $oContent->load( $aParams[ 'id' ] ) )
        {
            $sContent = '<div class="dd-shortcode-' . $this->getShortCode() . ' ' . ( $aParams[ 'class' ] ? ' ' . $aParams[ 'class' ] : '' ) . '">
                            <div class="box well well-sm categorytree dd-treeview-box">
                                <section>';

            $sContent .= '<div class="page-header h3">
                              ' . $aParams[ 'title' ] . '
                          </div>';

            $sContent .= '<div class="categoryBox">
                              <ol class="nav nav-pills nav-stacked cat-tree">';

            $oViewConfig = Registry::get( 'oxViewConfig' );
            $sCurrentContentId = $oViewConfig->getContentId();

            $aNodes = $oContent->getTreeviewNodes( $aParams[ 'id' ], false, Registry::getLang()->getTplLanguage(), true );
            foreach( $aNodes as $aNode )
            {
                $sContent .= $this->_renderNode( $aNode, $sCurrentContentId );
            }

            $sContent .= '</ol></div></section></div></div>';
        }

        return $sContent;
    }

    /**
     * Ermöglicht das Suchen von Aktionen
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function searchAction()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = Registry::getConfig();
        /** @var \OxidEsales\Eshop\Core\Request $oRequest */
        $oRequest = Registry::getRequest();

        $aContents = array();

        if( $oRequest->getRequestParameter( 'value' ) )
        {
            /** @var Content $oContent */
            $oContent = oxNew( Content::class );
            $oContent->load( $oRequest->getRequestParameter( 'value' ) );

            $aContents[] = array(
                'value' => $oContent->getId(),
                'label' => $oContent->oxcontents__oxtitle->value,
            );

        }
        elseif( $oRequest->getRequestParameter( 'search' ) )
        {
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
            $sSearch = $oRequest->getRequestParameter( 'search' );

            /** @var ContentList $oList */
            $oList = oxNew( ContentList::class );

            $sSelect = "SELECT *
                        FROM `oxcontents`
                        WHERE ( `OXSHOPID` = '" . $oConfig->getShopId() . "' OR `OXSHOPID` = '' )
                            AND `OXTYPE` != 0
                            AND ( 
                              `OXTITLE` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . " OR
                              `OXLOADID` LIKE " . $oDb->quote( "%" . $sSearch . "%" ) . "
                            )
                       ";

            $oList->selectString( $sSelect );

            /** @var Content $oContent */
            foreach( $oList as $oContent )
            {
                $aContents[] = array(
                    'value' => $oContent->getId(),
                    'label' => $oContent->oxcontents__oxtitle->value,
                );
            }
        }

        header( 'Content-Type: application/json' );
        Registry::getUtils()->showJsonAndExit( $aContents );
    }




    protected function _renderNode( $aNode, $sActiveNodeId = '' )
    {
        $sRenderedNode = '';
        /** @var Content $oContent */
        $oContent = oxNew( Content::class );
        $oContent->load( $aNode[ 'id' ] );

        if( $aNode[ 'id' ] == $sActiveNodeId )
        {
            $sRenderedNode .= '<li class="nav-item active">';
        }
        else
        {
            $sRenderedNode .= '<li class="nav-item exp">';
        }

        $sRenderedNode .= '<a class="nav-link" href="' . $oContent->getLink() . '" title="' . $oContent->oxcontents__oxtitle->value . '">';

        $sChildNodes = '';

        if( isset( $aNode[ 'children' ] ) && $aNode[ 'children' ] === true )
        {
            $sRenderedNode .= $oContent->oxcontents__oxtitle->value;

            $aChildNodes = $oContent->getTreeviewNodes( $aNode[ 'id' ], false, Registry::getLang()->getTplLanguage() );

            $sChildNodes .= '<ul class="nav nav-pills nav-stacked">';
            foreach( $aChildNodes as $aChildNode )
            {
                $sChildNodes .= $this->_renderNode( $aChildNode, $sActiveNodeId );
            }
            $sChildNodes .= '</ul>';
        }
        else
        {
            $sRenderedNode .= $oContent->oxcontents__oxtitle->value;
        }

        $sRenderedNode .= '</a>';
        $sRenderedNode .= $sChildNodes;
        $sRenderedNode .= '</li>';


        return $sRenderedNode;
    }
}