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
$serviceCaller = new \OxidEsales\TestingLibrary\ServiceCaller();
$testConfig = new \OxidEsales\TestingLibrary\TestConfig();

$serviceCaller->setParameter( 'importSql', '@' . __DIR__ . '/fixtures/testdata_' . strtolower( $testConfig->getShopEdition() ) . '.sql' );
$serviceCaller->callService( 'ShopPreparation', 1 );

define( 'oxADMIN_LOGIN', oxDb::getDb()->getOne( "select OXUSERNAME from oxuser where oxid='oxdefaultadmin'" ) );
define( 'oxADMIN_PASSWD', getenv( 'oxADMIN_PASSWD' ) ? getenv( 'oxADMIN_PASSWD' ) : 'admin' );

