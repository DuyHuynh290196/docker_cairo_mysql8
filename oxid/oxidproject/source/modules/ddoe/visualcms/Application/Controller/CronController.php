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
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class CronController
 */
class CronController extends FrontendController
{

    protected $_blDebug = false;


    public function render()
    {
        $this->run();
        exit;
    }

    public function run()
    {
        /** @var Config $oConfig */
        $oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();

        if( $oConfig->getConfigParam( 'sVisualEditorCronKey') && $oConfig->getConfigParam( 'sVisualEditorCronKey') != $oConfig->getRequestParameter( 'key' ) )
        {
            die( 'invalid key' );
        }

        $this->_blDebug = $oConfig->getRequestParameter( 'debug' ) ? true : false;

        if( $this->_blDebug )
        {
            echo '<pre>';
        }

        $this->_log( 'Starting template timer cronjob...' );
        $this->_log( 'Searching for templates...' );

        $oDb = DatabaseProvider::getDb( DatabaseProvider::FETCH_MODE_ASSOC);

        // Get templates with target
        $sSelect = "SELECT
                        `c`.`OXID`,
                        `c`.`OXCONTENT`,
                        `c`.`DDTMPLTARGETID`,
                        `c`.`DDTMPLTARGETDATE`,
                        `t`.`OXLOADID` AS 'DDTMPLTARGETIDENT'
                    FROM `oxcontents` AS `c`
                      INNER JOIN `oxcontents` AS `t`
                        ON `t`.`OXID` = `c`.`DDTMPLTARGETID`
                    WHERE `c`.`DDISTMPL` = 1
                      AND `c`.`DDTMPLTARGETID` != ''
                      AND YEAR( `c`.`DDTMPLTARGETDATE` ) != '0000'
                      AND `c`.`DDTMPLTARGETDATE` <= NOW() ";

        $aData = $oDb->getAll( $sSelect );

        if( $aData )
        {
            $this->_log( count( $aData ) . ' templates found' );

            // Overwriting templates
            foreach( $aData as $aRow )
            {
                $this->_log( 'overwriting "' . $aRow[ 'DDTMPLTARGETID' ] . '" with content from "' . $aRow[ 'OXID' ] . '"' );

                $aRow[ 'OXCONTENT' ] = preg_replace( "/\[\{veparse name=\"([^\"]*)\"/", '[{veparse name="' . $aRow[ 'DDTMPLTARGETIDENT' ] . '"', $aRow[ 'OXCONTENT' ] );

                $sUpdate = "UPDATE `oxcontents` SET `OXCONTENT` = '" . $aRow[ 'OXCONTENT' ] . "' WHERE `OXID` = '" . $aRow[ 'DDTMPLTARGETID' ] . "' ";
                $oDb->execute( $sUpdate );

                $sUpdate = "UPDATE `oxcontents` SET `DDTMPLTARGETID` = '', `DDTMPLTARGETDATE` = '' WHERE `OXID` = '" . $aRow[ 'OXID' ] . "' ";
                $oDb->execute( $sUpdate );

                $this->_log( '...done!' );
            }
        }
        else
        {
            $this->_log( 'no templates found' );
        }

        $this->_log( '...finish!' );

        if( $this->_blDebug )
        {
            echo '</pre>';
        }

    }


    protected function _log( $sMsg = '' )
    {
        if( $this->_blDebug )
        {
            echo '[' . date( 'Y-m-d H:i:s' ) . '] ' . $sMsg . "\n";
        }
    }

}