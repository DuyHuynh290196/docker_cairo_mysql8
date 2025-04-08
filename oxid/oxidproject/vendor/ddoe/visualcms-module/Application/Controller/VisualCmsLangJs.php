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

use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Core\Language;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class VisualCmsLangJs
 */
class VisualCmsLangJs extends FrontendController
{
    public function init()
    {
        /** @var oxLang $oLang */
        $oLang = oxNew( Language::class );

        header( 'Content-Type: application/javascript' );

        $oUtils = Registry::getUtils();
        $sJson = $oUtils->encodeJson( $oLang->getLanguageStrings() );
        $oUtils->showMessageAndExit( ";( function(g){ g.i18n = " . $sJson . "; })(window);" );
    }
}