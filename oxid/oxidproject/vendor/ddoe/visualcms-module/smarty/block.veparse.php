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

use \OxidEsales\VisualCmsModule\Application\Model\VisualEditor;
use \OxidEsales\Eshop\Core\Registry;

function smarty_block_veparse( $params, $content, &$smarty, &$repeat)
{
    $sName = isset( $params['name'] )? $params['name'] : null;
    $sCssClass = isset( $params['cssclass'] )? $params['cssclass'] : null;
    $sCustomCss = isset( $params['css'] )? $params['css'] : null;

    if ( !$repeat )
    {
        if( class_exists( VisualEditor::class ) )
        {
            /** @var ddvisualeditor $oEditor */
            $oEditor = oxNew( VisualEditor::class );
            $content = $oEditor->parse( $content, true, $sCssClass, $sCustomCss );

            if( !$sName )
            {
                $sName = md5( $content );
            }

            $oStr = getStr();
            $blHasSmarty = $oStr->strstr( $content, '[{' );

            if ( $blHasSmarty  )
            {
                $oConfig = Registry::getConfig();
                $content = Registry::get("oxUtilsView")->parseThroughSmarty( $content, $sName.md5($content), $oConfig->getActiveView(), true );
            }

            return $content;
        }
        else
        {
            return $content;
        }
    }

}
