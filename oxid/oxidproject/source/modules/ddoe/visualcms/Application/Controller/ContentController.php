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

namespace OxidEsales\VisualCmsModule\Application\Controller;

/**
 * Class ContentController
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\ContentController
 */
class ContentController extends ContentController_parent
{

    public function render()
    {
        /** @var \OxidEsales\Eshop\Core\ViewConfig $oViewConf */
        $oViewConf = $this->getViewConfig();

        $oContent = $this->getContent();

        if( $oContent && $oContent->oxcontents__ddfullwidth->value )
        {
            \OxidEsales\Eshop\Core\Registry::getConfig()->setConfigParam( 'blFullwidthLayout', true );
        }

        if( $oViewConf->isAzureTheme() )
        {
            $this->_sThisTemplate = 'ddoe_azure_content.tpl';
            $this->_sThisPlainTemplate = 'ddoe_azure_content_plain.tpl';
        }
        elseif( $oViewConf->isRoxiveTheme() )
        {
            $this->_sThisTemplate = 'ddoe_roxive_content.tpl';
            $this->_sThisPlainTemplate = 'ddoe_roxive_content_plain.tpl';
        }

        return parent::render();

    }


    public function getContentId()
    {
        $sContentId = parent::getContentId();

        if( $this->_oContent && ( !$this->_oContent->isActive() || $this->_oContent->oxcontents__ddisblock->value ) )
        {
            $this->_sContentId = null;
            $this->_oContent = null;

            return null;
        }

        return $sContentId;
    }

}
