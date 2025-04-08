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

use \OxidEsales\VisualCmsModule\Application\Model\VisualEditorBlock;
use \OxidEsales\Eshop\Core\Registry;

function smarty_block_veblock( $params, $content, &$smarty, &$repeat)
{
    $sName = isset( $params['name'] )? $params['name'] : null;

    if ( !$repeat )
    {
        if ( $sName )
        {
            static $aBlockCache = array();

            if ( isset( $aBlockCache[ $sName ] ) )
            {
                $content = $aBlockCache[ $sName ];
            }
            else
            {
                $oBlocks = Registry::get( VisualEditorBlock::class );

                if ( ( $sBlock = $oBlocks->getBlock( $sName ) ) )
                {
                    $aBlockCache[ $sName ] = $sBlock;
                    $content = $sBlock;
                }
            }

            $oStr = getStr();
            $blHasSmarty = $oStr->strstr( $content, '[{' );

            if ( $blHasSmarty  )
            {
                $oConfig = Registry::getConfig();
                $content = Registry::get( "oxUtilsView" )->parseThroughSmarty( $content, $sName.md5($content), $oConfig->getActiveView(), true );
            }
        }

        return $content;
    }

}