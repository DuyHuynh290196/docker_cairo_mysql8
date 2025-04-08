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

namespace OxidEsales\VisualCmsModule\Core;

/**
 * Class Theme
 *
 * @mixin \OxidEsales\Eshop\Core\Theme
 */
class Theme extends Theme_parent
{

    protected $_aActiveBlocks = null;

    public function getActiveBlocks()
    {
        if( $this->_aActiveBlocks == null )
        {
            ob_start();

            $sId = $this->getActiveThemeId();
            $sFilePath = \OxidEsales\Eshop\Core\Registry::getConfig()->getViewsDir() . $sId . "/theme.php";

            $aTheme = array();
            $aDynamicBlocks = array();

            if ( file_exists( $sFilePath ) && is_readable( $sFilePath ) )
            {
                include $sFilePath;
            }

            $this->_aActiveBlocks = $aDynamicBlocks;

            if( !empty( $aTheme[ 'parentTheme' ] ) )
            {
                $sFilePath = \OxidEsales\Eshop\Core\Registry::getConfig()->getViewsDir() . $aTheme['parentTheme' ] . "/theme.php";

                if ( file_exists( $sFilePath ) && is_readable( $sFilePath ) )
                {
                    include $sFilePath;
                }

                $this->_aActiveBlocks = array_merge( $this->_aActiveBlocks, $aDynamicBlocks );
            }

            ob_end_clean();
        }

        return $this->_aActiveBlocks;

    }

}