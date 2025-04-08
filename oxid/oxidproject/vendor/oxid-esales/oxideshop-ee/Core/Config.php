<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

use OxidEsales\Eshop\Core\Edition\EditionPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionRootPathProvider;
use OxidEsales\Eshop\Core\Edition\EditionSelector;
use OxidEsales\Eshop\Core\FileSystem\FileSystem;
use oxShop;

/**
 * Main shop configuration class.
 */
class Config extends \OxidEsales\EshopProfessional\Core\Config
{
    /** @var Serial Shop serial object. */
    protected $_oSerial = null;

    /** @var array Parent id of each shop. */
    protected $_aParentShopId = array();

    /**
     * Active Shop id setter
     *
     * @param string $shopId shop id
     *
     * @return null
     */
    public function setShopId($shopId)
    {
        $shopId = (int) $shopId;
        if ($shopId <= 0) {
            return;
        }

        parent::setShopId($shopId);
    }

    /**
     * Returns active shop ID.
     *
     * @return int
     */
    protected function calculateActiveShopId()
    {
        $shopId = null;
        if (!$this->isMall()) {
            $shopId = $this->getBaseShopId();
        }

        if (!$shopId && $this->getRequestParameter('shp')) {
            $shopId = (int)$this->getRequestParameter('shp');
        }

        if (!$shopId && $this->getRequestParameter('actshop')) {
            $shopId = (int)$this->getRequestParameter('actshop');
        }

        if (!$shopId) {
            $shopId = $this->_getShopIdFromSession();
        }

        if (!$shopId && !$this->isAdmin()) {
            $shopId = $this->_getShopIdFromLangUrls();
            if (!$shopId) {
                $shopId = $this->_getShopIdFromHost();
            }
        }

        if (!$shopId) {
            $shopId = parent::calculateActiveShopId();
        }

        return $shopId;
    }

    /**
     * Returns shop id from session, if it exists
     * This check needed for ERP functionality
     *
     * @return string|int
     */
    protected function _getShopIdFromSession() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {          
        $needStopSession = false;
        if (!\OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class)->getVar('blDeprecatedSubshopsInSessions') ) {
            if ($this->isAdmin()) {
                $adminSid = isset($_REQUEST['admin_sid']) ? $_REQUEST['admin_sid'] : null;
                $adminForceSid = isset($_REQUEST['admin_force_sid']) ? $_REQUEST['admin_force_sid'] : null;
                $sid = $adminForceSid ? $adminForceSid : $adminSid;
            } else {
                $userSid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;
                
                $userForceSid = null;
                if (!\OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\ConfigFile::class)->getVar('disallowForceSessionIdInRequest')) {
                    $userForceSid = isset($_REQUEST['force_sid']) ? $_REQUEST['force_sid'] : null;
                }
                
                $sid = $userForceSid ? $userForceSid : $userSid;
            }
            if ($sid) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_id($sid);
                    session_start();
                    // Remove pragma header after session start as it might affect system caching.
                    header_remove('Pragma');
                    $needStopSession = true;
                }
            }
        }

        $shopId = (int) \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('actshop');

        //abort session, we only needed the shop id at this point
        if ($needStopSession) {
            session_abort();
        }

        return $shopId;
    }

    /**
     * Returns shop id from host name, if current host is equal to sMallShopURL or sMallSSLShopURL
     *
     * @return string|int
     */
    protected function _getShopIdFromHost() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sShopId = null;
        $sQ = "select " . $this->getDecodeValueQuery() . " as oxvarvalue, oxshopid FROM oxconfig WHERE oxvarname = 'sMallShopURL' or oxvarname = 'sMallSSLShopURL' ";

        // Reading from slave here is ok (see ESDEV-3804 and ESDEV-3822).
        $oRs = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->select($sQ, false);

        if ($oRs !== false && $oRs->count() > 0) {
            while (!$oRs->EOF) {
                $sUrl = $oRs->fields['oxvarvalue'];

                if ($sUrl && $this->isCurrentUrl($sUrl)) {
                    $sShopId = (int) $oRs->fields['oxshopid'];
                    break;
                }
                $oRs->fetchRow();
            }
        }

        return $sShopId;
    }

    /**
     * check language urls for matching current ant return its shop id
     *
     * @return int
     */
    protected function _getShopIdFromLangUrls() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {

        $sQ = "select " . $this->getDecodeValueQuery() . " as oxvarvalue, oxshopid FROM oxconfig WHERE oxvarname = 'aLanguageURLs' ";
        // Reading from slave here is ok (see ESDEV-3804 and ESDEV-3822).
        $oRs = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC)->select($sQ, false);

        if ($oRs !== false && $oRs->count() > 0) {
            while (!$oRs->EOF) {
                $aLangUrls = $this->decodeValue("aarr", $oRs->fields['oxvarvalue']);
                if ($aLangUrls && is_array($aLangUrls)) {
                    $sId = (int) $oRs->fields['oxshopid'];
                    foreach ($aLangUrls as $sUrl) {
                        if ($sUrl && $this->isCurrentUrl($sUrl)) {
                            return $sId;
                        }
                    }
                }
                $oRs->fetchRow();
            }
        }

        return null;
    }

    /**
     * Checks if shop id must be added to urls and forms
     *
     * @return bool
     */
    public function mustAddShopIdToRequest()
    {
        $blMustAdd = false;
        //in case shop has no separate URL and is not base shop - adding shop id to url
        if ($this->getShopId() > 1 && ($this->isAdmin() || !$this->getConfigParam('sMallShopURL'))) {
            $blMustAdd = true;
        }

        return $blMustAdd;
    }

    /**
     * Tests if shop id is valid. Returns true if valid, false if not
     *
     * @param int $iShopId shop id
     *
     * @return bool
     */
    protected function _isValidShopId($iShopId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $isValid = parent::_isValidShopId($iShopId);
        if ($isValid) {
            $sSQL = "select 1 from oxshops where oxid = :oxid ";
            $isValid = (bool) \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne($sSQL, [
                ':oxid' => (int) $iShopId
            ]);
        }

        return $isValid;
    }

    /**
     * Load active shop and return it's parent shop id
     *
     * @param int $iShopId shop id
     *
     * @return int
     */
    public function getParentShopId($iShopId)
    {
        if (!array_key_exists($iShopId, $this->_aParentShopId)) {
            $this->_aParentShopId[$iShopId] = null;
            $oShop = oxNew(\OxidEsales\Eshop\Application\Model\Shop::class);
            if ($oShop->load($iShopId)) {
                $this->_aParentShopId[$iShopId] = $oShop->oxshops__oxparentid->value;
            }
        }

        return $this->_aParentShopId[$iShopId];
    }

    /**
     * Checks if the shop is in staging mode.
     *
     * @return bool
     */
    public function isStagingMode()
    {
        $oSerial = $this->getSerial();

        if ($oSerial->isFlagEnabled('staging_mode')) {
            return true;
        }

        return false;
    }

    /**
     * Returns OXID eShop edition.
     *
     * @deprecated since v6.0.0-rc.2 (2017-08-24); Use \OxidEsales\Facts\Facts::getEdition() instead.
     *
     * @return string
     */
    public function getEdition()
    {
        return "EE";
    }

    /**
     * @param $sBase
     * @param $sAbsBase
     * @param $sFile
     * @param $sDir
     * @param $blAdmin
     * @param $iLang
     * @param $iShop
     * @param $sTheme
     * @param $blAbsolute
     * @param $blIgnoreCust
     * @return bool|string
     */
    protected function getShopLevelDir($sBase, $sAbsBase, $sFile, $sDir, $blAdmin, $iLang, $iShop, $sTheme, $blAbsolute, $blIgnoreCust)
    {
        $sReturn = parent::getShopLevelDir($sBase, $sAbsBase, $sFile, $sDir, $blAdmin, $iLang, $iShop, $sTheme, $blAbsolute, $blIgnoreCust);

        if (!$sReturn && !$blAdmin && ($this->getBaseShopId() != $iShop)) {
            $iParentShop = $this->getParentShopId($iShop);
            if ($iParentShop && $iParentShop != $iShop) {
                $sReturn = $this->getDir($sFile, $sDir, $blAdmin, $iLang, $iParentShop, $sTheme, $blAbsolute);
            }
        }

        return $sReturn;
    }

    /**
     * Returns full eShop edition name
     *
     * @return string
     */
    public function getFullEdition()
    {
        $sEdition = $this->getEdition();
        if ($sEdition == "EE") {
            $sEdition = "Enterprise Edition";
        }

        return $sEdition;
    }

    /**
     * Counts OXID mandates
     *
     * @return int
     */
    public function getMandateCount()
    {
        return \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->getOne('select count(*) from oxshops');
    }

    /**
     * Checks if shop is MALL. Returns true on success.
     *
     * @return bool
     */
    public function isMall()
    {
        return true;
    }

    /**
     * Sets serial number for oxConfig object
     *
     * @param string $sSerial Serial
     */
    public function setSerial($sSerial)
    {
        $this->setConfigParam('sSerialNr', $sSerial);
    }


    /**
     * Updates or adds new shop configuration parameters to DB.
     * Arrays must be passed not serialized, serialized values are supported just for backward compatibility.
     *
     * @param string $sVarType Variable Type
     * @param string $sVarName Variable name
     * @param mixed  $sVarVal  Variable value (can be string, integer or array)
     * @param string $sShopId  Shop ID, default is current shop
     * @param string $sModule  Module name (empty for base options)
     */
    public function saveShopConfVar($sVarType, $sVarName, $sVarVal, $sShopId = null, $sModule = '')
    {
        parent::saveShopConfVar($sVarType, $sVarName, $sVarVal, $sShopId, $sModule);

        $this->executeDependencyEvent($sVarName);
    }

    /**
     * Execute dependencies
     *
     * @deprecated since 2019-11-23 (6.3.0 component version). All calls of the method will be removed soon.
     *
     * @param string $sVarName - config names
     *
     * @return bool
     */
    public function executeDependencyEvent($sVarName)
    {
    }

    /**
     * Execute dependencies
     *
     * @deprecated since v6.3.0 (2019-01-22); Reverse proxy functionality is extracted to module.
     *
     * @param string $sVarName - config names
     *
     * @return bool
     */
    protected function _effectsAllPages($sVarName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aParams = array(
            'blShowCookiesNotification', 'iSmartyPhpHandling', 'blUseTimeCheck', 'bl_perfLoadPrice'
        );

        return in_array($sVarName, $aParams);
    }

    /**
     * Execute dependencies
     *
     * @deprecated since v6.3.0 (2019-01-22); Reverse proxy functionality is extracted to module.
     *
     * @param string $sVarName - config names
     *
     * @return bool
     */
    protected function _efectsAllDetails($sVarName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aParams = array(
            'blUseStock', 'sStockWarningLimit', 'blStockOnDefaultMessage', 'blStockOffDefaultMessage',
            'iNrofSimilarArticles', 'iNrofCustomerWhoArticles', 'iNrofNewcomerArticles', 'iNrofCrossellArticles',
            'blOverrideZeroABCPrices', 'blNewArtByInsert', 'dDefaultVAT', 'blEnterNetPrice', 'blShowNetPrice',
            'blBidirectCross',
            'iRatingLogsTimeout', 'blVariantParentBuyable', 'blVariantInheritAmountPrice', 'blShowVariantReviews',
            'blUseMultidimensionVariants', 'iAttributesPercent', 'bl_perfLoadReviews', 'bl_perfLoadCrossselling',
            'bl_perfLoadAccessoires', 'bl_perfLoadCustomerWhoBoughtThis', 'bl_perfLoadSimilar', 'bl_perfLoadSelectLists',
            'bl_perfUseSelectlistPrice', 'bl_perfParseLongDescinSmarty', 'blRDFaEmbedding', 'aSearchCols'
        );

        return in_array($sVarName, $aParams);
    }

    /**
     * Execute dependencies
     *
     * @deprecated since v6.3.0 (2019-01-22); Reverse proxy functionality is extracted to module.
     *
     * @param string $sVarName - config names
     *
     * @return bool
     */
    protected function _effectsAllList($sVarName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aParams = array(
            'blShowSorting', 'aSortCols', 'blOverrideZeroABCPrices', 'blNewArtByInsert',
            'dDefaultVAT', 'blEnterNetPrice', 'blShowNetPrice', 'bl_rssCategories', 'blLoadVariants',
            'bl_perfLoadSelectListsInAList', 'bl_perfShowActionCatArticleCnt', 'aSearchCols'
        );

        return in_array($sVarName, $aParams);
    }

    /**
     * Execute dependencies
     *
     * @deprecated since v6.3.0 (2019-01-22); Reverse proxy functionality is extracted to module.
     *
     * @param string $sVarName - config names
     *
     * @return bool
     */
    protected function _effectsStartPage($sVarName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aParams = array(
            'blOverrideZeroABCPrices', 'blNewArtByInsert', 'dDefaultVAT', 'blEnterNetPrice', 'blShowNetPrice',
            // @deprecated 6.5.3 "News" feature will be removed completely
            'sCntOfNewsLoaded',
            // END deprecated
            'bl_rssTopShop', 'bl_rssBargain', 'bl_rssNewest',
            'iTop5Mode', 'iNewestArticlesMode', 'bl_perfLoadAktion', 'bl_perfLoadPriceForAddList',
            // @deprecated 6.5.3 "News" feature will be removed completely
            'bl_perfLoadNews',
            'bl_perfLoadNewsOnlyStart'
            // END deprecated
        );

        return in_array($sVarName, $aParams);
    }

    /**
     * Execute dependencies
     *
     * @deprecated since v6.3.0 (2019-01-22); Reverse proxy functionality is extracted to module.
     *
     * @param string $sVarName - config names
     *
     * @return array
     */
    protected function _getUrlGeneratorMethod($sVarName) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aReturnMethods = array();
        $aMethods = array(
            'blSearchUseAND'                 => array(array('setPage', array('search'))),
            'blEnableDownloads'              => array(
                array('setWidget', array('oxwservicemenu')),
                array('setWidget', array('oxwservicelist'))
            ),
            'bl_perfShowActionCatArticleCnt' => array(array('setWidget', array('oxwcategorytree'))),
            'aMustFillFields'                => array(array('setStaticPage', array('register'))),
            'aCurrencies'                    => array(array('setWidget', array('oxwcurrencylist'))),
            // @deprecated since v5.3 (2016-06-17); Listmania will be moved to an own module.
            'bl_rssRecommLists'              => array(array('setPage', array('recommlist'))),
            'bl_rssRecommListArts'           => array(array('setPage', array('recommlist'))),
            // END deprecated
            'blShowRememberMe'               => array(array('setWidget', array('oxwservicemenu'))),
            'blDontShowEmptyCategories'      => array(array('setWidget', array('oxwcategorytree'))),
            'bl_perfLoadManufacturerTree'    => array(
                array('setWidget', array('oxwmanufacturerlist')),
                array('setObject', array('oxManufacturer')),
            ),
            'bl_perfLoadCurrency'            => array(array('setWidget', array('oxwcurrencylist'))),
            'aLanguageParams'                => array(array('setWidget', array('oxwlanguagelist'))),
            'aLanguages'                     => array(array('setWidget', array('oxwlanguagelist'))),
            'bl_perfLoadLanguages'           => array(array('setWidget', array('oxwlanguagelist'))),
            'sRDFaBusinessEntityLoc'         => array(array('setObject', array('oxcontent'))),
            'sRDFaPaymentChargeSpecLoc'      => array(array('setObject', array('oxcontent'))),
            'sRDFaDeliveryChargeSpecLoc'     => array(array('setObject', array('oxcontent'))),
            'blInvitationsEnabled'           => array(array('setWidget', array('oxwservicelist'))),
            'aSearchCols'                    => array(array('setPage', array('search'))),
        );

        if (isset($aMethods[$sVarName])) {
            $aReturnMethods = $aMethods[$sVarName];
        }

        return $aReturnMethods;
    }

    /**
     * Returns true if current (or supplied as parameter) shop is multi-shop (multi-shop deals with all articles)
     *
     * @return bool
     */
    public function isMultiShop()
    {
        return (bool) $this->getActiveShop()->oxshops__oxismultishop->value;
    }

    /**
     * Performs actions required on shop change.
     * Don't forget to call this method on manual shop change.
     */
    public function onShopChange()
    {
        if (!$this->isAdmin()) {
            $mySession = $this->getSession();

            //#1355C - destroying session if $myConfig->blMallUsers not checked
            if (!$this->getConfigParam('blMallUsers')) {
                $mySession->initNewSession();
            } else {
                $oBasket = $mySession->getBasket();
                $oBasket->onUpdate();
            }
        }
    }

    /**
     * Function returns default shop ID
     *
     * @return string
     */
    public function getBaseShopId()
    {
        return '1';
    }

    /**
     * Loads and returns active shop object
     *
     * @return oxShop
     */
    public function getActiveShop()
    {
        $activeShop = parent::getActiveShop();
        $this->setSerial($activeShop->oxshops__oxserial->value);

        return $activeShop;
    }

    /**
     * Load any additional configuration on \OxidEsales\Eshop\Core\Config::init.
     */
    protected function loadAdditionalConfiguration()
    {
        $aOnlyMainShopVars = array('blMallUsers', 'aSerials', 'IMD', 'IMA', 'IMS');
        $this->_loadVarsFromDb($this->getBaseShopId(), $aOnlyMainShopVars);
    }

    /**
     * Initializes main shop tasks - processing of SEO calls, starting of session.
     */
    protected function initializeShop()
    {
        parent::initializeShop();

        //changed shop?
        if ($this->_iShopId && (int) $this->getSession()->getVariable('actshop') && $this->_iShopId != (int) $this->getSession()->getVariable('actshop')) {
            $this->onShopChange();
            $this->setShopId($this->_iShopId);
        }
    }

    /**
     * @inheritdoc
     */
    protected function getEditionTemplate($templateName)
    {
        $templatesPath = $this->getPathSelector()->getViewsDirectory();

        $fileSystem = oxNew(FileSystem::class);
        $templatePath = $fileSystem->combinePaths($templatesPath, $templateName);
        if (!$fileSystem->isReadable($templatePath)) {
            $templatePath = parent::getEditionTemplate($templateName);
        }

        return $templatePath;
    }

    /**
     * DIC imitation :)
     *
     * @return EditionPathProvider
     */
    private function getPathSelector()
    {
        return new EditionPathProvider(new EditionRootPathProvider(new EditionSelector(EditionSelector::ENTERPRISE)));
    }
}
