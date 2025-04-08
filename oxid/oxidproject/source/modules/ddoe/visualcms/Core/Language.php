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
 * Class Language
 *
 * @mixin \OxidEsales\Eshop\Core\Language
 */
class Language extends Language_parent
{
    public function getLanguageStrings( $iLang = null, $blAdminMode = null )
    {
        $aLang = array();

        foreach( $this->_getLangTranslationArray( $iLang, $blAdminMode ) as $sLangKey => $sLangValue )
        {
            $aLang[ $sLangKey ] = $sLangValue;
        }

        foreach( $this->_getLanguageMap( $iLang, $blAdminMode ) as $sLangKey => $sLangValue )
        {
            $aLang[ $sLangKey ] = $sLangValue;
        }

        if( count( $this->_aAdditionalLangFiles ) )
        {
            foreach( $this->_getLangTranslationArray( $iLang, $blAdminMode, $this->_aAdditionalLangFiles ) as $sLangKey => $sLangValue )
            {
                $aLang[ $sLangKey ] = $sLangValue;
            }
        }

        return $aLang;
    }
}