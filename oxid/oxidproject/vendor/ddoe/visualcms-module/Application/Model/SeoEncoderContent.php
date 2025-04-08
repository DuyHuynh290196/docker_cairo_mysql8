<?php
/**
 * This Software is the property of OXID eSales and is protected
 * by copyright law.
 *
 * Any unauthorized use of this software will be prosecuted by
 * civil and criminal law.
 *
 * @link          http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2017
 * @version       OXID eSales Visual CMS
 */

namespace OxidEsales\VisualCmsModule\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class SeoEncoderContent
 *
 * @mixin \OxidEsales\Eshop\Application\Model\SeoEncoderContent
 */
class SeoEncoderContent extends SeoEncoderContent_parent
{
    /**
     * Returns SEO uri for content object. Includes treeview hierarchy if any
     *
     * @param \OxidEsales\Eshop\Application\Model\Content $oCont        content category object
     * @param int                                         $iLang        language
     * @param bool                                        $blRegenerate if TRUE forces seo url regeneration
     *
     * @return string
     */
    public function getTreeviewContentUri( $oCont, $iLang = null, $blRegenerate = false )
    {
        if( !isset( $iLang ) )
        {
            $iLang = $oCont->getLanguage();
        }

        $sId = $oCont->getId();

        //load details link from DB
        if( $blRegenerate || !( $sSeoUrl = $this->_loadFromDb( 'oxContent', $sId, $iLang ) ) )
        {
            if( $iLang != $oCont->getLanguage() )
            {
                $oCont = oxNew( \OxidEsales\Eshop\Application\Model\Content::class );
                $oCont->loadInLang( $iLang, $sId );
            }

            $sSeoUrl = '';
            if( $oCont->oxcontents__ddparentcontent->value != '' && $oCont->getType() !== 0 )
            {
                $sSeoUrl .= $this->_getParentUri( $oCont->getId() );
            }

            $sSeoUrl .= $this->_prepareTitle( $oCont->oxcontents__oxtitle->value, false, $oCont->getLanguage() ) . '/';
            $sSeoUrl = $this->_processSeoUrl( $sSeoUrl, $oCont->getId(), $iLang );

            //$this->_saveToDb( 'oxcontent', $oCont->getId(), $oCont->getBaseStdLink( $iLang ), $sSeoUrl, $iLang );
        }

        return $sSeoUrl;
    }


    protected function _getParentUri( $sId )
    {
        $oDb = DatabaseProvider::getDb();
        $oDb->setFetchMode( DatabaseProvider::FETCH_MODE_ASSOC );
        $sUri = '';
        /** @var Content $oCont */
        $oCont = oxNew( Content::class );
        $oCont->load( $sId );

        $sTempParentId = $oCont->oxcontents__ddparentcontent->value;


        while ( !empty( $sTempParentId ) )
        {
            $oParentCont = oxNew( Content::class );
            $oParentCont->load( $sTempParentId );

            $sUri = $this->_prepareTitle( $oParentCont->oxcontents__oxtitle->value, false, $oCont->getLanguage() ) . '/' . $sUri;
            $sTempParentId = $oParentCont->oxcontents__ddparentcontent->value;
        }
        return $sUri;
    }
}