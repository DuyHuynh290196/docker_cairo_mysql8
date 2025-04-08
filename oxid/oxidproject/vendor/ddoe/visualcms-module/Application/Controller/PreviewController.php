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

use OxidEsales\Eshop\Application\Model\Content;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Field;

/**
 * Class PreviewController
 *
 * @mixin \OxidEsales\VisualCmsModule\Application\Controller\ContentController
 */
class PreviewController extends ContentController
{

    public function render()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $sId = $oConfig->getRequestParameter( 'id' );

        $oSession = Registry::getSession();
        $aContent = $oSession->getVariable( 'ddpreviewcontent_' . $sId );

        /** @var Content _oContent */
        $this->_oContent = oxNew( Content::class );
        $this->_oContent->assign( $aContent );

        $this->_oContent->oxcontents__ddactivefrom  = new Field( '0000-00-00', Field::T_RAW );
        $this->_oContent->oxcontents__ddactiveuntil = new Field( '0000-00-00', Field::T_RAW );
        $this->_oContent->oxcontents__oxactive      = new Field( 1, Field::T_RAW );
        $this->_oContent->oxcontents__oxloadid      = new Field( 'ddpreview', Field::T_RAW );

        $this->_oContent->setId( $sId );

        /*if( $oConfig->getRequestParameter( 'start' ) )
        {
            $this->setClassName( 'start' );
            $this->_sThisTemplate = 'page/shop/start.tpl';
        }*/

        return parent::render();

    }


    protected function _canShowContent( $sLoadId )
    {
        return true;
    }


    public function getLink( $iLang = null )
    {
        $sConstructedUrl = \OxidEsales\Eshop\Core\Registry::getConfig()->getShopCurrentURL( $iLang ) . $this->_getRequestParams();
        $sUrl = Registry::get( "oxUtilsUrl" )->processUrl( $sConstructedUrl, true, null, $iLang );

        return $sUrl;
    }

}
