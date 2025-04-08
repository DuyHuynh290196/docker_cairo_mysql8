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

namespace OxidEsales\VisualCmsModule\Application\Controller\Admin;

use OxidEsales\VisualCmsModule\Application\Model\Media;

use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class VisualCmsMedia
 */
class VisualCmsMedia extends AdminDetailsController
{

    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'dialog/ddoevisualcmsmedia.tpl';

    /**
     * @var Media
     */
    protected $_oMedia = null;

    protected $_sUploadDir = '';
    protected $_sThumbDir = '';
    protected $_iDefaultThumbnailSize = 0;


    public function init()
    {
        parent::init();

        if( $this->_oMedia === null )
        {
            $this->_oMedia = oxNew( Media::class );
        }

        $this->_sUploadDir = $this->_oMedia->getMediaPath();
        $this->_sThumbDir  = $this->_oMedia->getMediaPath();
        $this->_iDefaultThumbnailSize = $this->_oMedia->getDefaultThumbSize();

    }


    public function render()
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $iShopId = $oConfig->getConfigParam( 'blMediaLibraryMultiShopCapability' ) ? $oConfig->getActiveShop()->getShopId() : null;

        $this->_aViewData[ 'aFiles' ]       = $this->_getFiles( 0, $iShopId );
        $this->_aViewData[ 'iFileCount' ]   = $this->_getFileCount( $iShopId );
        $this->_aViewData[ 'sResourceUrl' ] = $this->_oMedia->getMediaUrl();
        $this->_aViewData[ 'sThumbsUrl' ]   = $this->_oMedia->getThumbnailUrl();

        return parent::render();
    }


    public function upload()
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        $sId = null;

        try
        {
            if ( $_FILES )
            {
                $this->_oMedia->createDirs();

                $sFileSize = $_FILES[ 'file' ][ 'size' ];
                $sFileType = $_FILES[ 'file' ][ 'type' ];

                $sSourcePath = $_FILES[ 'file' ][ 'tmp_name' ];
                $sDestPath = $this->_sUploadDir . $_FILES[ 'file' ][ 'name' ];

                $aFile = $this->_oMedia->uploadeMedia( $sSourcePath, $sDestPath, true );

                $sId = md5( $aFile[ 'filename' ] );
                $sThumbName = $aFile[ 'thumbnail' ];
                $sFileName = $aFile[ 'filename' ];

                $aImageSize = null;
                $sImageSize = '';

                if ( is_readable( $sDestPath ) && preg_match( "/image\//", $sFileType ) )
                {
                    $aImageSize = getimagesize( $sDestPath );
                    $sImageSize = ( $aImageSize ? $aImageSize[ 0 ] . 'x' . $aImageSize[ 1 ] : '' );
                }

                $oDb = DatabaseProvider::getDb();
                $iShopId = $oConfig->getActiveShop()->getShopId();

                $sInsert = "REPLACE INTO `ddmedia`
                              ( `OXID`, `OXSHOPID`, `DDFILENAME`, `DDFILESIZE`, `DDFILETYPE`, `DDTHUMB`, `DDIMAGESIZE` )
                            VALUES
                              ( ?, ?, ?, ?, ?, ?, ? );";

                $oDb->execute(
                    $sInsert,
                    array(
                        $sId,
                        $iShopId,
                        $sFileName,
                        $sFileSize,
                        $sFileType,
                        $sThumbName,
                        $sImageSize
                    )
                );
            }

            if ( $oConfig->getRequestParameter( 'src' ) == 'fallback' )
            {
                $this->fallback( true );
            }
            else
            {
                header( 'Content-Type: application/json' );
                die( json_encode(
                    array(
                        'success'   => true,
                        'id'        => $sId,
                        'file'      => $sFileName,
                        'filepath'  => $sDestPath,
                        'filetype'  => $sFileType,
                        'filesize'  => $sFileSize,
                        'imagesize' => $sImageSize,
                    )
                ) );
            }

        }
        catch( \Exception $e )
        {
            if ( $oConfig->getRequestParameter( 'src' ) == 'fallback' )
            {
                $this->fallback( false, true );
            }
            else
            {
                header( 'Content-Type: application/json' );
                die( json_encode(
                    array(
                        'success'   => false,
                        'id'        => $sId,
                    )
                ) );
            }
        }

    }


    public function remove()
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        if( $aIDs = $oConfig->getRequestParameter( 'id' ) )
        {
            $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );

            $sSelect = "SELECT `OXID`, `DDFILENAME`, `DDTHUMB` FROM `ddmedia` WHERE `OXID` IN(" . implode( ",", $oDb->quoteArray( $aIDs ) ) . "); ";
            $aData = $oDb->getAll( $sSelect );

            foreach( $aData as $aRow )
            {
                @unlink( $this->_sUploadDir . $aRow[ 'DDFILENAME' ] );

                if( $aRow[ 'DDTHUMB' ] )
                {
                    foreach( glob( $this->_sThumbDir . str_replace( 'thumb_' . $this->_iDefaultThumbnailSize . '.jpg', '*', $aRow[ 'DDTHUMB' ] ) ) as $sThumb )
                    {
                        @unlink( $sThumb );
                    }
                }

                $sDelete = "DELETE FROM `ddmedia` WHERE `OXID` = '" . $aRow[ 'OXID' ] . "'; ";
                $oDb->execute( $sDelete );
            }
        }

        exit();
    }


    public function fallback( $blComplete = false, $blError = false )
    {
        $oViewConf = $this->getViewConfig();

        $sFormHTML = '<html><head></head><body style="text-align:center;">
                          <form action="' . $oViewConf->getSelfLink() . 'cl=ddoevisualcmsmedia&fnc=upload&src=fallback" method="post" enctype="multipart/form-data">
                              <input type="file" name="file" onchange="this.form.submit();" />
                          </form>';

        if( $blComplete )
        {
            $sFormHTML .= '<script>window.parent.MediaLibrary.refreshMedia();</script>';

        }

        $sFormHTML .= '</body></html>';

        header( 'Content-Type: text/html' );
        die( $sFormHTML );
    }


    public function moreFiles()
    {
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
        $iStart = $oConfig->getRequestParameter( 'start' ) ? $oConfig->getRequestParameter( 'start' ) : 0;
        //$iShopId = $oConfig->getRequestParameter( 'oxshopid' ) ? $oConfig->getRequestParameter( 'oxshopid' ) : null;
        $iShopId = $oConfig->getConfigParam( 'blMediaLibraryMultiShopCapability' ) ? $oConfig->getActiveShop()->getShopId() : null;

        $aFiles = $this->_getFiles( $iStart, $iShopId );
        $blLoadMore = ( $iStart + 18 < $this->_getFileCount( $iShopId ) );

        header( 'Content-Type: application/json' );
        die( json_encode( array( 'files' => $aFiles, 'more' => $blLoadMore ) ) );
    }


    protected function _getFileCount( $iShopId = null )
    {
        $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
        $sSelect = "SELECT COUNT(*) AS 'count' FROM `ddmedia` WHERE 1 " . ( $iShopId != null ? "AND `OXSHOPID` = " . $oDb->quote( $iShopId ) . " " : "" );
        return $oDb->getOne( $sSelect );
    }


    protected function _getFiles( $iStart = 0, $iShopId = null )
    {
        /** Cast $iStart parameter to int in order to avoid SQL injection */
        $iStart = (int) $iStart;
        $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC );
        $sSelect = "SELECT * FROM `ddmedia` WHERE 1 " . ( $iShopId != null ? "AND `OXSHOPID` = " . $oDb->quote( $iShopId ) . " " : "" ) . "ORDER BY `OXTIMESTAMP` DESC LIMIT " . $iStart . ", 18 ";
        return $oDb->getAll( $sSelect );
    }

}
