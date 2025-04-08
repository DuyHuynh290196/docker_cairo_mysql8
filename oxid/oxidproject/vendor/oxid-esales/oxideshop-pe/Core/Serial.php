<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopProfessional\Core;

use oxRegistry;
use oxDb;

/**
 * Serial manager constants
 */
define("VER_OLD", 0);
define("VER_DEMO", -1);
define("VER_UNLICENSED", -2);
define("VER_PRO", 1);
define("VER_ENTERPRISE", 2);
define("VER_HOSTING", 3);
define("MAX_MODULE_COUNT", 140);

//So far we have 84 (since 2012 even more) modules and 20 of them are reserved bellow:
//reserved modules 32 through 52 for max day, max article count, version info, max mall shops
define("R1_START", 32); //max articles - "1111" - UNLIMITED, "0000" - MIN (20)
define("R2_START", 36); //max days "000" - UNLIMITED,"111" - Min (1000)
define("R3_START", 39); //"00" - PRO; "01" - ENTERPRISE
//bit 41: "0" - Prerelease, "1" - Final version
define("R4_START", 42); //Max mall shops "0000" - 0, "1111" - Unlimited. (just 1)
//since version 4.6.0 (2012) we add 3 more bits for mandate count/
//49-52 - reserved
define("RSTACKABLE", 53); //is stackable or not
//last reserved module
//maybe we need some more in future
define("R_END", 53);

//as we have no NaN in php, so we define
//safe enought "unlimited" number
define("UNLIMITED", 2e13);

//if you increase this, then serial will have less nonrandom patterns (eg. 8KBN8-KBN8K-BN)
//decreasing MANGLEITERATIONS improves processing speed
//but differently mangled serials are not compatible
define("MANGLEITERATIONS", 3);

/**
 * License key managing class.
 *
 * @internal Do not make a module extension for this class.
 *
 * @ignore   This class will not be included in documentation.
 */
class Serial
{
    public $sSerial = "";

    protected $_blCacheModules = true;

    //aditional name factor
    protected $_sName = "";

    protected $_sRetrievedName = "";

    protected $_aChars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

    //cached mangled and unmangled serials for this object
    protected $_aCachedNames = array();

    //flags array: key - flag name, value - bit number. Flag enable/disable shop functions by license.
    protected $_aFlags = array(
        'memcached_connector' => 87,
        'reverse_proxy'       => 86,
        'master_slave'        => 85,
        'staging_mode'        => 84,
        'demoshop'            => 60
    );

    /**
     * Edition EE, or PE
     *
     * @var string
     */
    protected $_sEdition = null;

    /**
     * Shop validation code
     *
     * @var string
     */
    protected $_sValidationCode = null;

    /**
     * Contains flags which are not automatically ON for TRIAL mode
     *
     * @var array
     */
    protected $_aNonDemoFlags = array('staging_mode', 'demoshop');

    /**
     * If shop is valid
     *
     * @var bool
     */
    private $_blShopValid = null;

    /**
     * Days count which defines how much days left to send last grace period notification.
     */
    private $_iDaysTillGraceEndsSendLastNotification = 1;

    /**
     * Initalize class
     *
     * @param string $sSerial serial number
     *
     * @return Serial
     */
    public function __construct($sSerial = "")
    {
        $this->sSerial = $sSerial;
    }

    /**
     * Sets the Edition
     *
     * @param int $iEdition Edition
     */
    public function setEd($iEdition)
    {
        if ($iEdition == 1) {
            $this->_sEdition = "PE";
        }
        if ($iEdition == 2) {
            $this->_sEdition = "EE";
        }
    }

    /**
     * Get Config object
     *
     * @return Config
     */
    public function getConfig()
    {
        return \OxidEsales\Eshop\Core\Registry::getConfig();
    }

    /**
     * Gets expiration email builder.
     *
     * @return ExpirationEmailBuilder
     */
    public function getExpirationEmailBuilder()
    {
        return oxNew(\OxidEsales\Eshop\Core\ExpirationEmailBuilder::class);
    }

    /**
     * Gets grace period reset email builder.
     *
     * @return GracePeriodResetEmailBuilder
     */
    public function getGracePeriodResetEmailBuilder()
    {
        return oxNew(\OxidEsales\Eshop\Core\GracePeriodResetEmailBuilder::class);
    }

    /**
     * Disables module caching
     */
    public function disableCacheModules()
    {
        $this->_blCacheModules = false;
    }

    /**
     * sets the name for serial.
     *
     * @param string $sName serial name
     */
    public function setName($sName)
    {
        $this->_sName = $sName;
    }

    /**
     * gets 6 letter id from serial
     *
     * @param string $sSerial serial
     *
     * @return string
     */
    public function getName($sSerial = "")
    {
        if ($sSerial == "") {
            $sSerial = $this->sSerial;
        }

        $this->_unmangleSerial($sSerial);

        return $this->_sRetrievedName;
    }

    /**
     * checks if serial is valid
     *
     * @param string $sSerial serial number
     *
     * @return bool
     */
    public function isValidSerial($sSerial)
    {
        $iVer = $this->detectVersion($sSerial);

        $sEdition = $this->_sEdition;

        if (!$sEdition) {
            $sEdition = \OxidEsales\Eshop\Core\Registry::getConfig()->getEdition();
        }

        if ($sEdition == "EE" && $iVer == 2) {
            // PE not allowed
            return false;
        }

        if ($sEdition == "PE" && $iVer == 3) {
            //EE not allowed
            return false;
        }

        if ($this->getName($sSerial)) {
            return true;
        }

        return false;
    }

    /**
     * checks for module in the serial
     *
     * @param int    $iModuleNr module id
     * @param string $sSerial   serial number
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "hasModule" in next major
     */
    protected function _hasModule($iModuleNr, $sSerial = "") // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $blhasModule = false;

        //caching
        static $cache_ahasModuleArray = array();

        if ($sSerial == "") {
            $sSerial = $this->sSerial;
        }

        //echo $cache_ahasModuleArray[$iModuleNr."__".$sSerial];

        if (isset($cache_ahasModuleArray[$iModuleNr . "__" . $sSerial])) {
            //echo $iModuleNr."__".$sSerial."....".$cache_ahasModuleArray[$iModuleNr."__".$sSerial]."<br>";
            return $cache_ahasModuleArray[$iModuleNr . "__" . $sSerial];
        }

        //$cache_ahasModuleArray[$iModuleNr."__".$sSerial] = false;

        if (!isset($iModuleNr) || !is_numeric($iModuleNr)) {
            return false;
        }

        //check if serial is unlicensed but just skip version flags as they may show unlicensed condition too (eg "111")
        if (!($iModuleNr >= R3_START && $iModuleNr < R4_START) && ($this->isUnlicensedSerial($sSerial) || !$sSerial)) {
            return false;
        }

        //$cache_ahasModuleArray[$iModuleNr."__".$sSerial] = true;

        //check TRIAL serial, but not for demo, mall, or reserved space flags
        $aFlagBits = array_flip($this->_aFlags);
        if (
            $iModuleNr > 1 &&
            ($iModuleNr < R1_START || $iModuleNr > R_END) &&
            !in_array($aFlagBits[$iModuleNr], $this->_aNonDemoFlags) &&
            $this->isDemoSerial($sSerial)
        ) {
            return true;
        }

        $sSerial = strtoupper($sSerial);
        $blhasModule = false;
        $sSerial = $this->_unmangleSerial($sSerial);
        $iModuleNr = $iModuleNr * 1;
        $aVals = array(1, 2, 4, 8, 16);
        $iByte = (int)floor($iModuleNr / 5);
        $iCheckByte = $aVals[$iModuleNr % 5];

        if ($iByte < strlen($sSerial)) {
            $blhasModule = (strpos($this->_aChars, $sSerial[$iByte]) & $iCheckByte);
        } else {
            $blhasModule = false;
        }

        $cache_ahasModuleArray[$iModuleNr . "__" . $sSerial] = $blhasModule;

        return $blhasModule;
    }


    /**
     * Checks given flag status: enable or disable.
     *
     * @param string $sFlagName flag name
     * @param string $sSerial   serial key
     *
     * @return bool
     */
    public function isFlagEnabled($sFlagName, $sSerial = "")
    {
        $blEnabled = false;

        if (isset($this->_aFlags[$sFlagName])) {
            $blEnabled = $this->_hasModule($this->_aFlags[$sFlagName], $sSerial);
        }

        return $blEnabled;
    }

    /**
     * returns serial with added module within it.
     *
     * @param int    $iModuleNr module id
     * @param string $sSerial   serial number
     *
     * @return string
     */
    public function addModule($iModuleNr, $sSerial = "")
    {

        if ($sSerial == "") {
            $sSerial = $this->sSerial;
        }

        $sSerial = $this->_unmangleSerial($sSerial);
        $iModuleNr = $iModuleNr * 1;
        $aVals = array(1, 2, 4, 8, 16);
        $iByte = floor($iModuleNr / 5);
        $iSetByte = $aVals[$iModuleNr % 5];
        if ($iByte > strlen($sSerial) - 1) {
            $sSerial .= str_repeat("A", $iByte - strlen($sSerial) + 1);
        }

        //setting byte
        $sSerial[$iByte] = $this->_aChars[strpos($this->_aChars, $sSerial[$iByte]) | $iSetByte];
        $sSerial = $this->_mangleSerial($sSerial);

        return $sSerial;
    }

    /**
     * adds additional serial to existing serial and returns the sum of two serials
     *
     * @param string $sExistingSerial existing serial
     * @param string $sAddSerial      additional serial
     *
     * @return string
     */
    public function addSerial($sExistingSerial, $sAddSerial)
    {
        $aAdditionalModules = array();

        if ($this->isUnlicensedSerial($sExistingSerial) && $this->isValidSerial($sAddSerial)) {
            return $sAddSerial;
        }

        if ($this->isDemoSerial($sAddSerial)) {
            return $sAddSerial;
        }

        for ($i = 0; $i < MAX_MODULE_COUNT; $i++) {
            if ($this->_hasModule($i, $sAddSerial)) {
                //here we are sure that new serial has modules
                //then if existing serial is TRIAL serial we just return new serial;
                if ($this->isDemoSerial($sExistingSerial)) {
                    return $sAddSerial;
                }

                //we add new available module from new module
                $aAdditionalModules[] = $i;
            }

            if ($this->_hasModule($i, $sExistingSerial)) {
                //we add new available module from existing serial, but not all as
                //certain modules which represents article and day count we don't want to sum
                // we just skip certain modules, that this area would be replaced with the new one

                // I excluded mandate count as this was overwritten by modules
                if (($i < R1_START || $i > R_END) || ($i >= R4_START || $i <= R4_START + 3)) {
                    $aAdditionalModules[] = $i;
                }
            }
        }

        $this->setName($this->getName($sAddSerial));
        $sNewSerial = $this->_getBlankSerial();
        for ($i = 0; $i < count($aAdditionalModules); $i++) {
            $sNewSerial = $this->addModule($aAdditionalModules[$i], $sNewSerial);
        }

        return $sNewSerial;
    }

    /**
     * Get TRIAL serial with all modules set ON except mall.
     *
     *
     * @return string
     */
    public function getDemoSerial()
    {
        //return $this->mangleSerial("799999DS999999999999");
        $sSerial = $this->_getBlankSerial();
        $sSerial = $this->addModule(0, $sSerial);

        return $sSerial;
    }

    /**
     * Detects if serial is TRIAL serial.
     *
     * @param string $sSerial serial number
     *
     * @return bool
     */
    public function isDemoSerial($sSerial)
    {
        //TRIAL serial for eShop 2.1.3
        if ($sSerial == "Q8R4-KPPT-6BUM-5TRJ" || strpos(strtolower($sSerial), "demo") !== false) {
            return true;
        }

        if ($this->_hasModule(0, $sSerial)) {
            return true;
        }

        return false;
    }

    /**
     * Checks if serial is unlicensed
     *
     * @param string $sSerial serial number
     *
     * @return bool
     */
    public function isUnlicensedSerial($sSerial)
    {
        //caching value
        static $cache_aBlUnlicensed = array();

        if (isset($cache_aBlUnlicensed[$sSerial])) {
            return $cache_aBlUnlicensed[$sSerial];
        }

        $cache_aBlUnlicensed[$sSerial] = true;

        if ($this->isInvalidBetaSerial($sSerial)) {
            return true;
        }

        if (strtolower($sSerial) == "unlicensed" || !$sSerial || !$this->isValidSerial($sSerial)) {
            return true;
        }

        $cache_aBlUnlicensed[$sSerial] = false;

        return false;
    }

    /**
     * Checks if given beta serial can be used for current shop version.
     *
     * @param string $sSerial
     *
     * @return bool
     */
    protected function isInvalidBetaSerial($sSerial)
    {
        $isInvalid = false;
        if ($this->_hasModule(39, $sSerial) && $this->_hasModule(40, $sSerial) && $this->_hasModule(41, $sSerial)) {
            $isInvalid = true;
        }
        return $isInvalid;
    }

    /**
     * Detects serial version 0(old version), 1(TRIAL), 2(PE), 3(EE), 4(EE beta, available from eshop 5.0)
     *
     * @param string $sSerial serial number
     *
     * @return int
     */
    public function detectVersion($sSerial)
    {

        //0 - version is olders than 3.0
        if (strlen($sSerial) > 0 && strlen($sSerial) < 22) {
            return 0;
        }

        //1 - TRIAL or unlicensed
        if ($this->isUnlicensedSerial($sSerial) || $this->isDemoSerial($sSerial) || !$sSerial) {
            return 1;
        }

        //2 - Pro
        if (!$this->_hasModule(39, $sSerial) && !$this->_hasModule(40, $sSerial) /*&& $this->_hasModule(41, $sSerial)*/) {
            return 2;
        }

        //3 - Enterprise
        if (!$this->_hasModule(39, $sSerial) && $this->_hasModule(40, $sSerial) /*&& $this->_hasModule(41, $sSerial)*/) {
            return 3;
        }

        //4 - EE Beta (available from 5.0)
        if ($this->_hasModule(39, $sSerial) && $this->_hasModule(40, $sSerial) && $this->_hasModule(41, $sSerial)) {
            return 4;
        }

        return 1;
    }

    /**
     * Checks if it is beta serial.
     *
     * @return bool
     */
    public function isBetaSerial()
    {
        return $this->detectVersion($this->sSerial) == 4;
    }

    /**
     * Returns max days
     *
     * @param string $sSerial serial number
     *
     * @return int
     */
    public function getMaxDays($sSerial)
    {
        $iMaxDays = 0;
        $b1 = ($this->_hasModule(R2_START, $sSerial)) ? "1" : "0";
        $b2 = ($this->_hasModule(R2_START + 1, $sSerial)) ? "1" : "0";
        $b3 = ($this->_hasModule(R2_START + 2, $sSerial)) ? "1" : "0";
        $sBitStr = $b1 . $b2 . $b3;

        switch ($sBitStr) {
            case "000":
                $iMaxDays = 0;
                break;
            case "001":
                $iMaxDays = 10;
                break;
            case "010":
                $iMaxDays = 20;
                break;
            case "011":
                $iMaxDays = 30;
                break;
            case "100":
                $iMaxDays = 60;
                break;
            case "101":
                $iMaxDays = 90;
                break;
            case "110":
                $iMaxDays = 120;
                break;
            case "111":
                $iMaxDays = UNLIMITED;
                break;
        }

        return $iMaxDays;
    }

    /**
     * return max article number of serial
     *
     * @param string $sSerial serial number
     *
     * @return int
     */
    public function getMaxArticles($sSerial)
    {
        $iMaxArticles = 0;
        $b1 = ($this->_hasModule(R1_START, $sSerial));
        $b2 = ($this->_hasModule(R1_START + 1, $sSerial));
        $b3 = ($this->_hasModule(R1_START + 2, $sSerial));
        $b4 = ($this->_hasModule(R1_START + 3, $sSerial));

        if ($b1) {
            $iMaxArticles += 8;
        }
        if ($b2) {
            $iMaxArticles += 4;
        }
        if ($b3) {
            $iMaxArticles += 2;
        }
        if ($b4) {
            $iMaxArticles += 1;
        }

        $iMaxArticles *= 1000;

        if ($iMaxArticles == 15000) {
            $iMaxArticles = UNLIMITED;
        }

        return $iMaxArticles;
    }

    /**
     * Returns max shop count
     *
     * @param string $sSerial serial number
     *
     * @return int
     */
    public function getMaxShops($sSerial)
    {
        $iMax = 0;
        $b1 = ($this->_hasModule(R4_START, $sSerial));
        $b2 = ($this->_hasModule(R4_START + 1, $sSerial));
        $b3 = ($this->_hasModule(R4_START + 2, $sSerial));
        $b4 = ($this->_hasModule(R4_START + 3, $sSerial));
        $b5 = ($this->_hasModule(R4_START + 4, $sSerial));
        $b6 = ($this->_hasModule(R4_START + 5, $sSerial));
        $b7 = ($this->_hasModule(R4_START + 6, $sSerial));
        $b8 = ($this->_hasModule(R4_START + 7, $sSerial));

        $s1 = $b1 ? "1" : "0";
        $s2 = $b2 ? "1" : "0";
        $s3 = $b3 ? "1" : "0";
        $s4 = $b4 ? "1" : "0";
        $s5 = $b5 ? "1" : "0";
        $s6 = $b6 ? "1" : "0";
        $s7 = $b7 ? "1" : "0";

        //echo $s1.$s2.$s3.$s4."<br>";

        if ($b1) {
            $iMax += 8;
        }
        if ($b2) {
            $iMax += 4;
        }
        if ($b3) {
            $iMax += 2;
        }
        if ($b4) {
            $iMax += 1;
        }

        //added at 2012
        if ($b5) {
            $iMax += 16;
        }

        if ($b6) {
            $iMax += 32;
        }

        if ($b7) {
            $iMax += 64;
        }
        //end of 2012 addition


        if ($iMax == 15) {
            $iMax = UNLIMITED;
        }


        //remove the unlimited hole here
        if ($iMax >= 16) {
            $iMax -= 1;
        }

        return $iMax;
    }

    /**
     * Detects if serial is stackable
     *
     * @param string $sSerial serial number
     *
     * @return bool
     */
    public function isStackable($sSerial)
    {
        return (bool) $this->_hasModule(RSTACKABLE, $sSerial);
    }

    /**
     * Internal checksum, returns two chars, this is up to 1024 variants in this case.
     *
     * @param string $sIn Internal checksum
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getChecksum" in next major
     */
    protected function _getChecksum($sIn) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $iCS = strlen($sIn);
        for ($i = 0; $i < strlen($sIn); $i++) {
            $iCS += ($iCS % 654231) * ord($sIn[$i]);
        }
        $iCS = $iCS % 1024;

        $iCS1 = $iCS >> 5;
        $iCS2 = $iCS & 31;

        $sCS = $this->_aChars[$iCS1] . $this->_aChars[$iCS2];

        return $sCS;
    }

    /**
     * Transforms serial to final state
     *
     * @param string $sUnmangledSerial serial
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "mangleSerial" in next major
     */
    protected function _mangleSerial($sUnmangledSerial = "") // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($sUnmangledSerial == "") {
            $sUnmangledSerial = $this->sSerial;
        }

        $sUnmangledSerial = $this->_hashName() . $sUnmangledSerial;

        //$sShift = ord(md5('OXID'));
        $sShift = ord(md5('OXID'));

        $sMangledSerial = "";

        for ($j = 0; $j < MANGLEITERATIONS; $j++) {
            $sMangledSerial = "";
            for ($i = 0; $i < strlen($sUnmangledSerial); $i++) {
                $sMangledSerial .= $this->_charShift($sUnmangledSerial[$i], $sShift);
                $sShift = ord(md5($sMangledSerial[$i]));
            }
            $sUnmangledSerial = $sMangledSerial;
        }
        $sMangledSerial = $this->_getChecksum($sMangledSerial) . $sMangledSerial;
        $sMangledSerial = chunk_split($sMangledSerial, 5, "-");
        if (strpos($sMangledSerial, "-", strlen($sMangledSerial) - 1)) {
            $sMangledSerial = substr($sMangledSerial, 0, -1);
        }

        return $sMangledSerial;
    }

    /**
     * transforms mangled serial to readable form
     *
     * @param string $sMangledSerial serial
     *
     * @return mixed
     * @deprecated underscore prefix violates PSR12, will be renamed to "unmangleSerial" in next major
     */
    protected function _unmangleSerial($sMangledSerial = "") // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {

        if ($sMangledSerial == "") {
            $sMangledSerial = $this->sSerial;
        }

        if (!$sMangledSerial) {
            return;
        }

        $sPrimaryMangledSerial = $sMangledSerial;

        static $cache_aUnmangledSerials = array();

        if (isset($cache_aUnmangledSerials[$sPrimaryMangledSerial]) && isset($this->_aCachedNames[$sPrimaryMangledSerial]) && $this->_blCacheModules) {
            $this->_sRetrievedName = $this->_aCachedNames[$sPrimaryMangledSerial];

            return $cache_aUnmangledSerials[$sPrimaryMangledSerial];
        }

        $sMangledSerial = str_replace("-", "", $sMangledSerial);

        $iCS = substr($sMangledSerial, 0, 2);

        $sMangledSerial = substr($sMangledSerial, 2);

        if ($iCS != $this->_getChecksum($sMangledSerial)) {
            return false;
        }


        $sShift = ord(md5('OXID'));

        for ($j = 0; $j < MANGLEITERATIONS; $j++) {
            $sUnmangledSerial = "";
            for ($i = 0; $i < strlen($sMangledSerial); $i++) {
                $sUnmangledSerial .= $this->_charShift($sMangledSerial[$i], -$sShift);
                $sShift = ord(md5($sMangledSerial[$i]));
            }

            if ($j < 2) {
                $sShift = ord(md5($sUnmangledSerial[strlen($sUnmangledSerial) - 1]));
            } else {
                $sShift = ord(md5('OXID'));
            }
            $sUnmangledSerial[0] = $this->_charShift($sMangledSerial[0], -$sShift);

            $sMangledSerial = $sUnmangledSerial;
        }

        //substracting name
        $this->_sRetrievedName = substr($sUnmangledSerial, 0, 6);
        $sUnmangledSerial = substr($sUnmangledSerial, 6);

        // cache
        $this->sUnMangledSerial = $sUnmangledSerial;

        $cache_aUnmangledSerials[$sPrimaryMangledSerial] = $sUnmangledSerial;
        $this->_aCachedNames[$sPrimaryMangledSerial] = $this->_sRetrievedName;

        return $sUnmangledSerial;
    }

    /**
     * gets blank serial with 0 modules
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getBlankSerial" in next major
     */
    protected function _getBlankSerial() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_mangleSerial("AAAAAAAAAAAAAAAAAAAAAA");
    }

    /**
     * cycle shifts $cIn character by $sShift positions, if sShift>0 it shifts to right
     *
     * @param char   $cIn    character
     * @param string $sShift number of positions
     *
     * @return mixed
     * @deprecated underscore prefix violates PSR12, will be renamed to "charShift" in next major
     */
    protected function _charShift($cIn, $sShift) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $cIn = strtoupper($cIn);

        $aChars = $this->_aChars;

        $sCurrent = strpos($aChars, $cIn);

        if ($sCurrent === false) {
            return $cIn;
        }

        $sCurrent = $sCurrent + $sShift;

        while (!($sCurrent >= 0 && $sCurrent < strlen($aChars))) {
            $sCurrent = $sCurrent - strlen($aChars) * (abs($sCurrent) / $sCurrent);
        }

        return $aChars[$sCurrent];
    }

    /**
     * converts name to 6 char hash
     *
     * @param string $sName name
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "hashName" in next major
     */
    protected function _hashName($sName = "") // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($sName == "") {
            $sName = $this->_sName;
        }
        $sName = strtoupper($sName) . 'ABCD23';
        for ($i = 0; $i < strlen($sName); $i++) {
            if (strpos($this->_aChars, $sName[$i]) === false) {
                $sName = str_replace($sName[$i], "", $sName);
            }
        }
        $sName = substr($sName, 0, 6);

        return $sName;
    }

    /**
     * Returns shop validation message.
     *
     * @return string
     */
    public function getValidationMessage()
    {
        return $this->_sValidationCode;
    }

    /**
     * Checks if grace period started.
     *
     * @return bool
     */
    public function isGracePeriodStarted()
    {
        $oConfig = $this->getConfig();
        $sGracePeriodStarted = $oConfig->getConfigParam('sBackTag');

        return !empty($sGracePeriodStarted);
    }

    /**
     * Checks if grace period expired.
     *
     * @return bool
     */
    public function isGracePeriodExpired()
    {
        $blExpired = false;
        $oConfig = $this->getConfig();
        $sStartTime = $oConfig->getConfigParam('sBackTag');

        if ($sStartTime && is_numeric($sStartTime)) {
            $sMaxDays = 7;
            $iSecondsInDay = 60 * 60 * 24;
            $iExpiryTime = $sStartTime + ($sMaxDays * $iSecondsInDay);
            $oUtilsDate = \OxidEsales\Eshop\Core\Registry::getUtilsDate();
            $iCurrentTime = $oUtilsDate->getTime();

            if (($iCurrentTime > $iExpiryTime)) {
                $blExpired = true;
            }
        }

        return $blExpired;
    }

    /**
     * Returns whether shop has valid serial.
     *
     * @return bool
     */
    public function isShopValid()
    {
        if (is_null($this->_blShopValid)) {
            $this->_blShopValid = $this->_isBetaShopValid() && $this->_isShopLicensed() && !$this->_isSerialExpired() && $this->_isCorrectMandateAmount();
        }

        return $this->_blShopValid;
    }

    /**
     * Checks is serial is active.
     * If invalid - redirects to shop offline.
     *
     * @return bool
     */
    public function validateShop()
    {
        $blValid = true;

        if (!$this->isShopValid()) {
            if (!$this->isGracePeriodStarted()) {
                $this->_startGracePeriod();
                $this->sendExpirationEmail();
            } elseif ($this->_needSendExpirationEmailLastTime()) {
                $this->getConfig()->saveShopConfVar('bool', 'blExpirationEmailSent', true);
                $this->sendExpirationEmail();
            } elseif ($this->isGracePeriodExpired()) {
                $this->_setCheckSerialEachCall(true);
                $blValid = false;
            }
        } elseif ($this->isGracePeriodStarted()) {
            $this->_setCheckSerialEachCall(false);
            $this->_removeGracePeriod();
            $this->getGracePeriodResetEmailBuilder()->build()->send();
        }

        return $blValid;
    }

    /**
     * Build and send shop license key expiration email
     */
    protected function sendExpirationEmail()
    {
        $expirationEmailBuilder = $this->getExpirationEmailBuilder();
        $expirationEmailBuilder->build($this->_daysLeftTillGraceExpires())->send();
    }

    /**
     * Returns days count which show how much days left till grace period expires.
     *
     * @return int|null Function returns null if grace period did not start.
     */
    private function _daysLeftTillGraceExpires() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $iDays = null;
        if ($this->isGracePeriodStarted()) {
            $sStartTime = $this->getConfig()->getConfigParam('sBackTag');
            if (is_numeric($sStartTime)) {
                $iDifference = \OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime() - $sStartTime;
                $iGracePeriodTimeInDays = floor($iDifference / (3600 * 24));
                $iDays = intval(7 - $iGracePeriodTimeInDays);
            }
        }

        return $iDays;
    }

    /**
     * Starts the grace period.
     * Sets to config option current time.
     */
    private function _startGracePeriod() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = $this->getConfig();
        $iCurrentTime = \OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime();
        $oConfig->saveShopConfVar('string', 'sBackTag', $iCurrentTime, $oConfig->getBaseShopId());
        $oConfig->setConfigParam('sBackTag', $iCurrentTime);
    }

    /**
     * Removes the grace period.
     * Deletes config option for grace period.
     */
    private function _removeGracePeriod() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = $this->getConfig();
        $oConfig->saveShopConfVar('string', 'sBackTag', '', $oConfig->getBaseShopId());
        $oConfig->setConfigParam('sBackTag', '');
    }

    /**
     * Returns whether beta shop is valid.
     *
     * @return bool
     */
    private function _isBetaShopValid() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $blValid = !$this->isBetaSerial() || $this->_isBetaShopVersion();

        if (!$blValid) {
            $this->_sValidationCode = 'shop_unlicensed';
        }

        return $blValid;
    }

    /**
     * Returns whether shop is licensed.
     *
     * @return bool
     */
    private function _isShopLicensed() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = $this->getConfig();
        $blValid = !($oConfig->getConfigParam('sShopVar') == 'unlc' || $this->isUnlicensedSerial($this->sSerial));

        if (!$blValid) {
            $this->_sValidationCode = 'shop_unlicensed';
        }

        return $blValid;
    }

    /**
     * Returns whether serial is expired.
     *
     * @return bool
     */
    private function _isSerialExpired() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $blExpired = false;
        $oConfig = $this->getConfig();
        $sStartTime = $oConfig->getConfigParam('sTagList');
        $sMaxDays = $oConfig->getConfigParam('IMD');
        $iSecondsInDay = 60 * 60 * 24;
        $iExpiryTime = $sStartTime + ($sMaxDays * $iSecondsInDay);
        $iCurrentTime = \OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime();

        if (!$sStartTime || ($iCurrentTime > $iExpiryTime)) {
            $blExpired = true;
            $this->_sValidationCode = 'serial_expired';
        }

        return $blExpired;
    }

    /**
     * Returns whether mandate amount is correct.
     *
     * @return bool
     * @deprecated underscore prefix violates PSR12, will be renamed to "isCorrectMandateAmount" in next major
     */
    protected function _isCorrectMandateAmount() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return true;
    }

    /**
     * Returns whether shop version is beta
     *
     * @return bool
     */
    private function _isBetaShopVersion() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = $this->getConfig();

        return strpos($oConfig->getVersion(), "_");
    }

    /**
     * Sets whether to check serial on each call
     *
     * @param string $sCalculateEachCall
     */
    private function _setCheckSerialEachCall($sCalculateEachCall) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = $this->getConfig();
        $oConfig->saveShopConfVar('bool', 'blShopStopped', $sCalculateEachCall, $oConfig->getBaseShopId());
    }

    /**
     * Returns configuration value from database.
     *
     * @param string $sVarName Variable name
     *
     * @return string
     */
    private function _getConfigValueFromDB($sVarName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $oConfig = $this->getConfig();
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sQ = "select " . $oConfig->getDecodeValueQuery() . " as oxvarvalue from oxconfig where oxshopid = :oxshopid and oxvarname = :oxvarname";
        $sResult = $oDb->getOne($sQ, [
            ':oxshopid' => $oConfig->getShopId(),
            ':oxvarname' => $sVarName
        ]);

        return $sResult;
    }

    /**
     * Checks if send notification last time.
     *
     * @return bool
     */
    private function _needSendExpirationEmailLastTime() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        return $this->_daysLeftTillGraceExpires() === $this->_iDaysTillGraceEndsSendLastNotification && !$this->_getConfigValueFromDB('blExpirationEmailSent');
    }
}

/*
//SERIAL DEMONSTRATION CODE SNIPPETS
*/

//more information on handling could be found at:
//http://fish/trac/wiki/eshop_license_keys_handling
//http://fish/trac/wiki/eshop_serial_logics


//$time = microtime()+time();
/*
//test serial with names and modules
$oSerial = new Serial();
$sS = $oSerial->getBlankSerial();


echo "Blank serial: $sS<br>";

//$sS = $oSerial->addModule(1, $sS);
//$sS = $oSerial->addModule(2, $sS);
$sS = $oSerial->addModule(109, $sS);
$sS = $oSerial->addModule(13, $sS);
$sS = $oSerial->addModule(42, $sS);
$sS = $oSerial->addModule(43, $sS);
$sS = $oSerial->addModule(44, $sS);
$sS = $oSerial->addModule(45, $sS);
$sS = $oSerial->addModule(46, $sS);
$sS = $oSerial->addModule(47, $sS);
//$sS = $oSerial->addModule(0, $sS);

echo "Serial with modules: $sS <br>";
*/
/*
$oSerial = new Serial();
$sS = 'U6TCM-838CF-B9SK7-P3PR4-GNVLK-K6GKK';
*/
/*
//now trying to get em back
for ($i = 0; $i<300; $i++)
{
    if (!($i%10))
        echo "---------<br>";
    if ($oSerial->hasModule($i, $sS))
        echo "Serial $sS has module $i <br>";
}*/


/*
//get name
$oSerial = new Serial();
$oSerial->setName("Company name");
$oSerial->unmangleSerial($sS)."<br>";
echo $oSerial->sRetrievedName."<br>";
*/


/*
//calucalitng the sum of serials
$oSerial = new Serial();
echo "The sum of serials: ".$oSerial->addSerial('3CR8-AXVY-VS8Z-HCK8-8QAM', "TC2K-7R5P-R4J6-HX7T-9RSR")."<br>";

*/

//echo microtime()+time()-$time."<br>";

/*echo "<br>";

$oSerial = new Serial();
//$sSerial = 'LBK37-MAMB9-PKGN5-T9EM9-3SA7T-ZZ46G';
//$sSerial = 'V83Q6-YCWCP-EM932-MU4X5-MR4GN-VLKK6';
$sSerial = '3Q3EQ-U4562-Y9JTE-2N6LP-JTJ9K-GNVLK';
if ($oSerial->_hasModule(39, $sSerial))
    echo "39<br>";
if ($oSerial->_hasModule(40, $sSerial))
    echo "40<br>";
if ($oSerial->_hasModule(41, $sSerial))
    echo "41<br>";*/
