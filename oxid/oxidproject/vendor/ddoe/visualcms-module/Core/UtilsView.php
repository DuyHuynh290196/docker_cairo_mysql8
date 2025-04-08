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

use OxidEsales\VisualCmsModule\Application\Model\VisualEditor;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class UtilsView
 *
 * @mixin \OxidEsales\Eshop\Core\UtilsView
 */
class UtilsView extends UtilsView_parent
{

    /**
     * Initializes and returns templates directory info array
     *
     * @return array
     */
    public function getTemplateDirs()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = Registry::getConfig();

        parent::getTemplateDirs();

        $this->setTemplateDir( $oConfig->getModulesDir( true ) . 'ddoe/visualcms/application/views/tpl/' );
        $this->setTemplateDir( $oConfig->getModulesDir( true ) . 'ddoe/visualcms/application/views/roxive/tpl/' );
        $this->setTemplateDir( $oConfig->getModulesDir( true ) . 'ddoe/visualcms/application/views/azure/tpl/' );

        if ( $this->isAdmin() )
        {
            $this->setTemplateDir( $oConfig->getModulesDir( true ) . 'ddoe/visualcms/application/views/admin/tpl/' );
        }

        return $this->_aTemplateDir;
    }


    /**
     * sets properties of smarty object
     *
     * @param object $oSmarty template processor object (smarty)
     */
    protected function _fillCommonSmartyProperties( $oSmarty )
    {
        parent::_fillCommonSmartyProperties( $oSmarty );

        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = Registry::getConfig();

        include_once $oConfig->getModulesDir() . 'ddoe/visualcms/smarty/block.veparse.php';
        include_once $oConfig->getModulesDir() . 'ddoe/visualcms/smarty/block.veblock.php';

        // Switched to manual register of blocks, because in some cases inner content was cached
        $oSmarty->register_block( 'veparse', 'smarty_block_veparse', false );
        $oSmarty->register_block( 'veblock', 'smarty_block_veblock', false );

        if ( $oConfig->isDemoShop() )
        {
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'html_entity_decode';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'addslashes';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'getimagesize';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'json_encode';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'json_decode';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'substr';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'sprintf';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'rtrim';
            $oSmarty->security_settings[ 'MODIFIER_FUNCS' ][] = 'ltrim';
        }
    }


    /**
     * Runs long description through smarty. If you pass array of data
     * to process, array will be returned, if you pass string - string
     * will be passed as result
     *
     * @param mixed  $sDesc       description or array of descriptions ( array( [] => array( _ident_, _value_to_process_ ) ) )
     * @param string $sOxid       current object id
     * @param oxview $oActView    view data to use its view data (optional)
     * @param bool   $blRecompile force to recompile if found in cache
     *
     * @return mixed
     */
    public function parseThroughSmarty( $sDesc, $sOxid = null, $oActView = null, $blRecompile = false )
    {
        /** @var VisualEditor $oEditor */
        $oEditor = oxNew( VisualEditor::class );

        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = Registry::getConfig();

        $blDemoMode = $oConfig->isDemoShop();

        if(  $blDemoMode && $oEditor->isVisualEditorContent( $sDesc ) )
        {
            $oConfig->setConfigParam( 'blDemoShop', 0 );
        }

        $sContent = parent::parseThroughSmarty( $sDesc, $sOxid, $oActView, $blRecompile );

        if( $blDemoMode )
        {
            $oConfig->setConfigParam( 'blDemoShop', 1 );
        }

        return $sContent;

    }

}