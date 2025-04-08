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

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Module\Module;

/**
 * Class Events
 */
class Events
{

    /**
     * An array of SQL statements, that will be executed only at the first time of module installation.
     *
     * @var array
     */
    private static $_aSetupSQLs = array(

        // oxcontents table

        array(
            'type'   => 'column',
            'column' => 'DDHIDETITLE',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDHIDETITLE` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0;",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDHIDESIDEBAR',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDHIDESIDEBAR` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0;",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDISBLOCK',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents`
                         ADD  `DDISBLOCK` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0,
                         ADD  `DDBLOCK` VARCHAR( 250 ) NOT NULL DEFAULT '',
                         ADD  `DDOBJECTTYPE` VARCHAR( 50 ) NOT NULL DEFAULT '',
                         ADD  `DDOBJECTID` CHAR( 32 ) NOT NULL DEFAULT '';",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDISLANDING',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDISLANDING` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0;",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDISTMPL',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDISTMPL` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0; ",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDACTIVEFROM',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDACTIVEFROM` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00';",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDACTIVEUNTIL',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDACTIVEUNTIL` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'; ",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDCSSCLASS',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDCSSCLASS` VARCHAR( 100 ) NOT NULL DEFAULT '';",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDCUSTOMCSS',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDCUSTOMCSS` TEXT NOT NULL DEFAULT '';",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDTMPLTARGETID',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDTMPLTARGETID` CHAR( 32 ) NOT NULL DEFAULT '';",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDTMPLTARGETDATE',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDTMPLTARGETDATE` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00'; ",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDPARENTCONTENT',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDPARENTCONTENT` CHAR( 32 ) NOT NULL DEFAULT ''; ",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDSORTING',
            'table'  => 'oxcontents',
            'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDSORTING` INT( 11 ) NOT NULL DEFAULT 0; ",
        ),

        // ddmedia table

        array(
            'type'  => 'table',
            'table' => 'ddmedia',
            'sql'   =>  "CREATE TABLE IF NOT EXISTS `ddmedia` (
                          `OXID` char(32) character set latin1 collate latin1_general_ci NOT NULL DEFAULT '',
                          `DDFILENAME` varchar(255) NOT NULL DEFAULT '',
                          `DDFILESIZE` int(10) unsigned NOT NULL DEFAULT 0,
                          `DDFILETYPE` varchar(50) NOT NULL DEFAULT '',
                          `DDTHUMB` varchar(255) NOT NULL DEFAULT '',
                          `OXTIMESTAMP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          PRIMARY KEY (`OXID`)
                        ) ENGINE=InnoDB; ",
        ),

        array(
            'type'   => 'column',
            'column' => 'DDIMAGESIZE',
            'table'  => 'ddmedia',
            'sql'    => "ALTER TABLE  `ddmedia` ADD  `DDIMAGESIZE` VARCHAR( 100 ) DEFAULT '' AFTER  `DDTHUMB`; ",
        ),

        array(
            'type'   => 'column',
            'column' => 'OXSHOPID',
            'table'  => 'ddmedia',
            'sql'    => "ALTER TABLE  `ddmedia` ADD `OXSHOPID` INT(10) UNSIGNED NOT NULL DEFAULT 1 AFTER `OXID`;",
        ),

    );


    /**
     * An array of SQL statements, that will be executed only at the update of module.
     *
     * @var array
     */
    private static $__aUpdateSQLs = array(

        "1.1.0" => array(

            array(
                'type'   => 'column',
                'column' => 'DDFULLWIDTH',
                'table'  => 'oxcontents',
                'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDFULLWIDTH` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0",
            ),

        ),

        // todo: check version for next update
        "3.2.0" => array(

            array(
                'type'   => 'column',
                'column' => 'DDPLAINTEXT',
                'table'  => 'oxcontents',
                'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDPLAINTEXT` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT 0",
            ),

            array(
                'type'  => 'update',
                'sql'   => "UPDATE `oxcontents`
                            SET `DDPLAINTEXT` = 1
                            WHERE `OXLOADID` IN
                                  (
                                    'oxemailfooterplain', 
                                    'oxregisterplainaltemail', 
                                    'oxuserorderemailendplain', 
                                    'oxupdatepassinfoplainemail', 
                                    'oxnewsletterplainemail', 
                                    'oxuserorderplainemail', 
                                    'oxadminorderplainemail', 
                                    'oxregisterplainemail', 
                                    'oxordersendplainemail', 
                                    'oxuserordernpplainemail', 
                                    'oxadminordernpplainemail', 
                                    'oxstartmetakeywords', 
                                    'oxstartmetadescription'
                                  ) ",
            ),

            array(
                'type'  => 'update',
                'sql'   => "UPDATE `oxcontents`
                            SET `OXTITLE` = `OXTITLE_1`, `OXCONTENT` = `OXCONTENT_1` 
                            WHERE `OXTITLE` = '' AND `OXTITLE_1` != '' AND DDISTMPL = 1 ",
            ),

            array(
                'type'  => 'update',
                'sql'   => "UPDATE `oxcontents`
                            SET `OXTITLE` = `OXTITLE_2`, `OXCONTENT` = `OXCONTENT_2` 
                            WHERE `OXTITLE` = '' AND `OXTITLE_2` != '' AND DDISTMPL = 1 ",
            ),

            array(
                'type'  => 'update',
                'sql'   => "UPDATE `oxcontents`
                            SET `OXTITLE` = `OXTITLE_3`, `OXCONTENT` = `OXCONTENT_3` 
                            WHERE `OXTITLE` = '' AND `OXTITLE_3` != '' AND DDISTMPL = 1 ",
            ),

        ),

        "3.3.0" => array(

            array(
                'type'   => 'column',
                'column' => 'DDPARENTCONTENT',
                'table'  => 'oxcontents',
                'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDPARENTCONTENT` CHAR( 32 ) NOT NULL DEFAULT ''; ",
            ),

            array(
                'type'   => 'column',
                'column' => 'DDSORTING',
                'table'  => 'oxcontents',
                'sql'    => "ALTER TABLE  `oxcontents` ADD  `DDSORTING` INT( 11 ) NOT NULL DEFAULT 0; ",
            ),

        ),

    );


    /**
     * An array of SQL statements, that will be executed on module activation.
     *
     * @var array
     */
    private static $__aActivateSQLs = array(
        // '',
    );


    /**
     * An array of SQL statements, that will be executed on module deactivation.
     *
     * @var array
     */
    private static $__aDeactivateSQLs = array(
        // '',
    );


    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        self::setupModule();

        self::updateModule();

        self::activateModule();

        self::regenerateViews();

        self::clearCache();
    }


    private static function __copyOldMediaFiles()
    {
        $sOldMediaDir =  getShopBasePath() . 'out/pictures/ddvisualeditor/';

        if( is_dir( $sOldMediaDir ) )
        {
            $sNewMediaDir = getShopBasePath() . 'out/pictures/ddmedia/';

            if( !is_dir( $sNewMediaDir ) )
            {
                mkdir( $sNewMediaDir );
            }

            foreach( glob( $sOldMediaDir . '*' ) as $sFile )
            {
                if ( !is_dir( $sFile ) )
                {
                    @copy( $sFile, $sNewMediaDir . basename( $sFile ) );
                    @unlink( $sFile );
                }
            }

            $sOldEditorMediaDir = $sOldMediaDir . 'wysiwyg/';

            if( is_dir( $sOldEditorMediaDir ) )
            {
                foreach ( glob( $sOldEditorMediaDir . '*' ) as $sFile )
                {
                    if ( !is_dir( $sFile ) )
                    {
                        @copy( $sFile, $sNewMediaDir . basename( $sFile ) );
                        @unlink( $sFile );
                    }
                }

                rmdir( $sOldEditorMediaDir );
            }

            rmdir( $sOldMediaDir );
        }
    }


    /**
     * Execute action on deactivate event
     */
    public static function onDeactivate()
    {
        self::executeSQLs( self::$__aDeactivateSQLs );

        self::clearCache();
    }

    /**
     * Execute the sql at the first time of the module installation.
     */
    private static function setupModule()
    {
        self::executeSQLs( self::$_aSetupSQLs );

        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = Registry::getConfig();
        $oConfig->saveShopConfVar( 'bool', 'blModuleWasEnabled', 'true', $oConfig->getShopId(), 'module:ddoevisualcms' );

    }

    /**
     * Activate module after installation.
     */
    private static function activateModule()
    {
        self::executeSQLs( self::$__aActivateSQLs );
    }

    /**
     * Updates module if it was already installed.
     */
    private static function updateModule()
    {
        /** @var \OxidEsales\Eshop\Core\Config $oConfig */
        $oConfig = Registry::getConfig();

        /** @var Module $oModule */
        $oModule = oxNew( Module::class );
        $oModule->load( 'ddoevisualcms' );

        $sCurrentVersion   = $oModule->getInfo( 'version' );
        $sInstalledVersion = $oConfig->getShopConfVar( 'iInstallledVersion', $oConfig->getShopId(), 'module:ddoevisualcms' );

        if( !$sInstalledVersion || version_compare( $sInstalledVersion, $sCurrentVersion, '<' ) )
        {
            if( self::$__aUpdateSQLs )
            {
                foreach( self::$__aUpdateSQLs as $sUpdateVersion => $aSQLs )
                {
                    if( !$sInstalledVersion || version_compare( $sUpdateVersion, $sInstalledVersion, '>' ) )
                    {
                        self::executeSQLs( $aSQLs );

                        if( $sUpdateVersion == '1.1.0' )
                        {
                            self::__copyOldMediaFiles();
                        }
                    }
                }
            }

            $oConfig->saveShopConfVar( 'str', 'iInstallledVersion', $sCurrentVersion, $oConfig->getShopId(), 'module:ddoevisualcms' );
        }

    }

    /**
     * Regenerate views for changed tables
     */
    protected static function regenerateViews()
    {
        $oDbMetaDataHandler = oxNew(DbMetaDataHandler::class );
        $oDbMetaDataHandler->updateViews();
    }

    /**
     * Empty cache
     */
    private static function clearCache()
    {
        $sTmpDir = getShopBasePath() . "/tmp/";

        foreach (glob($sTmpDir . "*oxcontents*.txt") as $sFileName) {
            @unlink($sFileName);
        }

        /** @var \OxidEsales\Eshop\Core\UtilsView $oUtilsView */
        $oUtilsView = Registry::get( 'oxUtilsView' );
        $sSmartyDir = $oUtilsView->getSmartyDir();

        if( $sSmartyDir && is_readable( $sSmartyDir ) )
        {
            foreach( glob( $sSmartyDir . '*' ) as $sFile )
            {
                if ( !is_dir( $sFile ) )
                {
                    @unlink( $sFile );
                }
            }
        }

        // Initialise Smarty
        $oUtilsView->getSmarty( true );
    }

    /**
     * Check if table exists
     *
     * @param string $sTableName table name
     *
     * @return bool
     */
    protected static function tableExists($sTableName)
    {
        $oDbMetaDataHandler = oxNew( DbMetaDataHandler::class );
        return $oDbMetaDataHandler->tableExists($sTableName);
    }

    /**
     * Check if field exists in table
     *
     * @param string $sFieldName field name
     * @param string $sTableName table name
     *
     * @return bool
     */
    protected static function fieldExists($sFieldName, $sTableName)
    {
        $oDbMetaDataHandler = oxNew(DbMetaDataHandler::class );
        return $oDbMetaDataHandler->fieldExists($sFieldName, $sTableName);
    }

    /**
     * Executes given sql statements.
     *
     * @param $aSQLs array
     */
    private static function executeSQLs( $aSQLs )
    {
        if( count( $aSQLs ) > 0 )
        {
            foreach ( $aSQLs as $aSQL ) {

                if( $aSQL[ 'type' ] == 'column' )
                {
                    // Check if fields already exists, if not add them to the table.
                    if ( !self::fieldExists( $aSQL[ 'column' ], $aSQL[ 'table' ] ) )
                    {
                        self::executeSQL( $aSQL[ 'sql' ] );
                    }
                }
                elseif( $aSQL[ 'type' ] == 'table' )
                {
                    // Check if table was already created, if not create it.
                    if ( !self::tableExists( $aSQL[ 'table' ] ) )
                    {
                        self::executeSQL( $aSQL[ 'sql' ] );
                    }
                }
                else
                {
                    // Execute sql without checks
                    self::executeSQL( $aSQL[ 'sql' ] );
                }
            }
        }
    }

    /**
     * Executes given sql statement.
     *
     * @param string $sSQL Sql to execute.
     */
    private static function executeSQL( $sSQL )
    {
        @DatabaseProvider::getDb()->execute( $sSQL );
    }
}
