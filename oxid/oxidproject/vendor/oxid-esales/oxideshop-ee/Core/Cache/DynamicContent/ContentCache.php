<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\Cache\DynamicContent;

use oxDb;
use oxSystemComponentException;
use OxidEsales\Eshop\Application\Model\Contract\CacheBackendInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererBridgeInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;

/**
 * Partial content caching class
 */
class ContentCache extends \OxidEsales\Eshop\Core\Base
{
    /** @var array All available backends, sorted by priority. */
    protected $_aAllBackends = null;

    /** @var array */
    protected $_aSystemBackends = array(
        'ZS_SHM'  => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendShmCacheConnector',
        'ZS_DISK' => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\ZendDiskCacheConnector',
        'OXID'    => '\OxidEsales\EshopEnterprise\Core\Cache\DynamicContent\Connector\DefaultCacheConnector',
    );

    /**
     * Cache life time in seconds
     *
     * @var int
     */
    protected $_iCacheLifetime = null;

    /**
     * Cacheable classes names array
     *
     * @var array
     */
    protected $_aCachableClasses = null;

    /**
     * Cache backend
     *
     * @var CacheBackendInterface
     */
    protected $_oBackend = null;

    /**
     * placeholder for session id in cleared data
     */
    const SESSION_ID_PLACEHOLDER = '[__SESSION_ID_PLACEHOLDER__]';

    /**
     * placeholder for session stoken in cleared data
     */
    const SESSION_STOKEN_PLACEHOLDER = '[__SESSION_STOKEN_PLACEHOLDER__]';

    /**
     * placeholder for force_sid=SID in urls
     */
    const SESSION_FULL_ID_PLACEHOLDER = '[__SESSION_FULL_ID_PLACEHOLDER__]';

    /**
     * placeholder for force_sid=SID in urls, add an (&) before original placeholder
     */
    const SESSION_FULL_ID_AMP_PLACEHOLDER = '[__SESSION_FULL_ID_AMP_PLACEHOLDER__]';

    /**
     * placeholder for force_sid=SID in urls, add an (?) before original placeholder
     */
    const SESSION_FULL_ID_QUE_PLACEHOLDER = '[__SESSION_FULL_ID_QUE_PLACEHOLDER__]';

    /**
     * fetch backend object
     *
     * @return CacheBackendInterface
     * @deprecated underscore prefix violates PSR12, will be renamed to "getBackend" in next major
     */
    protected function _getBackend() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if ($this->_oBackend) {
            return $this->_oBackend;
        }
        $sBackend = $this->getSelectedBackend();
        $aBackends = $this->_getAllBackends();

        $this->_oBackend = oxNew($aBackends[$sBackend]);
        $this->_oBackend->cacheSetTTL($this->getCacheLifeTime());

        return $this->_oBackend;
    }

    /**
     * retrieve all installed backends
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "getAllBackends" in next major
     */
    protected function _getAllBackends() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        if (isset($this->_aAllBackends)) {
            return $this->_aAllBackends;
        }
        $aUserBackends = $this->getConfig()->getConfigParam('aUserCacheBackends');
        if (is_array($aUserBackends) && count($aUserBackends)) {
            $this->_aAllBackends = array_merge($aUserBackends, $this->_aSystemBackends);
        } else {
            $this->_aAllBackends = $this->_aSystemBackends;
        }

        return $this->_aAllBackends;
    }

    /**
     * find the selected backend for usage in shop
     *
     * @return string
     */
    public function getSelectedBackend()
    {
        $sBackend = $this->getConfig()->getConfigParam('sCacheBackend');
        if ($sBackend && $this->isBackendAvailable($sBackend)) {
            return $sBackend;
        }

        return $this->_getFirstSuitedBackend();
    }

    /**
     * find first suited backend and return its id
     *
     * @throws oxSystemComponentException
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "getFirstSuitedBackend" in next major
     */
    protected function _getFirstSuitedBackend() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        foreach (array_keys($this->_getAllBackends()) as $sBackend) {
            if ($this->isBackendAvailable($sBackend)) {
                return $sBackend;
            }
        }

        $e = oxNew(\OxidEsales\Eshop\Core\Exception\SystemComponentException::class);
        $e->setComponent('oxCache');
        throw $e;
    }

    /**
     * check if backend is available
     *
     * @param string $sBackend backend id
     *
     * @return bool
     */
    public function isBackendAvailable($sBackend)
    {
        $aBackends = $this->_getAllBackends();
        $sClass = $aBackends[$sBackend];
        if ($sClass && class_exists($sClass) && in_array('OxidEsales\EshopEnterprise\Application\Model\Contract\CacheBackendInterface', class_implements($sClass))) {
            return call_user_func(array($sClass, 'isAvailable'));
        }

        return false;
    }

    /**
     * return array of available backend ids
     *
     * @return array
     */
    public function getAvailableBackends()
    {
        $aRet = array();
        foreach (array_keys($this->_getAllBackends()) as $sBackend) {
            if ($this->isBackendAvailable($sBackend)) {
                $aRet[] = $sBackend;
            }
        }

        return $aRet;
    }

    /**
     * Cache lifetime setter
     *
     * @param int $iCacheLifeTime cache lifetime
     */
    public function setCacheLifetime($iCacheLifeTime = 360)
    {
        if (isset($iCacheLifeTime)) {
            $this->_iCacheLifetime = $iCacheLifeTime;
        }
    }

    /**
     * Returns cache lifetime value
     *
     * @return int
     */
    public function getCacheLifeTime()
    {
        if (!isset($this->_iCacheLifetime)) {
            $this->setCacheLifetime(\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('iCacheLifeTime'));
        }

        return $this->_iCacheLifetime;
    }

    /**
     * Cacheable classes setter
     *
     * @param array $aCachableClasses cacheable classes
     */
    public function setCachableClasses($aCachableClasses)
    {
        $this->_aCachableClasses = $aCachableClasses;
    }

    /**
     * Cacheable classes setter
     *
     * @return null
     */
    public function getCachableClasses()
    {
        if (!isset($this->_iCacheLifetime)) {
            $this->setCachableClasses(\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('aCachableClasses'));
        }

        return $this->_aCachableClasses;
    }

    /**
     * Returns, whether a given class is cachable
     *
     * @param string $sViewName a class name
     *
     * @return bool
     */
    public function isViewCacheable($sViewName)
    {
        $blCachable = false;
        $aClasses = $this->getCachableClasses();
        if (is_array($aClasses)) {
            $blCachable = in_array($sViewName, $aClasses);
        }

        return $blCachable;
    }

    /**
     * Adds an ID and a content to the cache.
     *
     * @param string $sCacheId a cache ID
     * @param string $sContent a content
     * @param string $sResetOn a reset (date?)
     */
    public function put($sCacheId, $sContent, $sResetOn = '')
    {
        $sId = md5($sCacheId);
        $sContent = $this->_cleanSensitiveData($sContent);

        $oCacheBackend = $this->_getBackend();
        $oCacheBackend->cacheRemoveKey($sId);
        $oCacheBackend->cachePut($sId, $sContent);

        $this->_addId($sCacheId, getStr()->strlen($sContent), $sResetOn);
    }

    /**
     * Retrieves a cache entry by it's id.
     *
     * @param string $sCacheId an id for the information to be retrieved
     *
     * @return mixed
     */
    public function get($sCacheId)
    {
        $sContent = false;
        $sId = $this->getCacheId($sCacheId);

        if ($sId && ($sContent = $this->_getBackend()->cacheGet($sId))) {
            $this->_addHit($sCacheId);
        }

        return $sContent;
    }

    /**
     * Returns cache id if it exists and is not expired and zend caching is on.
     *
     * @param string $sCacheId caching contents id
     *
     * @return string
     */
    public function getCacheId($sCacheId)
    {
        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        return \OxidEsales\Eshop\Core\DatabaseProvider::getMaster()->getOne("select oxid from `oxcache` where oxid = :oxid and oxexpire > :oxexpire", [
            ':oxid' => md5($sCacheId),
            ':oxexpire' => time()
        ]);
    }

    /**
     * Removes a cache entry by a given id.
     *
     * @param string $sCacheId a id to be removed from cache
     */
    public function remove($sCacheId)
    {
        $sId = md5($sCacheId);
        $this->_getBackend()->cacheRemoveKey($sId);
        $this->_removeId($sCacheId);
    }

    /**
     * Adds an object to cachem by serialising it.
     *
     * @param string $sCacheId an identifier
     * @param object $oObject  an object to be added to cache
     * @param string $sResetOn a reset (date?)
     */
    public function putObject($sCacheId, $oObject, $sResetOn = '')
    {
        $this->put($sCacheId, serialize($oObject), $sResetOn);
    }

    /**
     * Retrieves a cached object by it's id, unserialising the object
     *
     * @param string $sCacheId an id of the cached object
     *
     * @return mixed
     */
    public function getObject($sCacheId)
    {
        $oObject = $this->get($sCacheId);
        if ($oObject !== false) {
            $oObject = unserialize($oObject);
        }

        return $oObject;
    }

    /**
     * Deleted db and file cache content
     *
     * @param array $blResetFileCache reset file cache
     */
    public function reset($blResetFileCache = true)
    {
        if ($blResetFileCache) {
            \OxidEsales\Eshop\Core\Registry::getUtils()->oxResetFileCache();
        }
        if ($this->isActive()) {
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from oxcache");
            $this->_getBackend()->cacheClear();
        }
    }

    /**
     * returns true if the content cache was activated by configuration
     * it should be used to check if the admin has enabled that feature before performing
     * operation on the cache
     *
     * @return bool
     */
    public function isActive()
    {
        return (bool) \OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('blUseContentCaching');
    }


    /**
     * Resets cache according to special reset conditions passed by params
     *
     * @param array $aResetOn reset conditions array
     * @param bool  $blUseAnd reset precise level ( AND's conditions SQL )
     */
    public function resetOn($aResetOn, $blUseAnd = false)
    {
        $sResetConditions = '';
        $sSep = $blUseAnd ? ' and ' : ' or ';
        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        $masterDb = \OxidEsales\Eshop\Core\DatabaseProvider::getMaster();
        foreach ($aResetOn as $sKey => $sVal) {
            if ($sResetConditions) {
                $sResetConditions .= $sSep;
            }
            $sResetConditions .= " oxreseton like " . $masterDb->quote("%|$sVal=$sKey%") . " ";
        }

        $oCacheBackend = $this->_getBackend();

        $sQ = "select oxid from oxcache where oxexpire > " . time() . " and ( $sResetConditions ) ";

        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        $oRs = $masterDb->select($sQ, false);
        if ($oRs != false && $oRs->count() > 0) {
            while (!$oRs->EOF) {
                $oCacheBackend->cacheRemoveKey($oRs->fields[0]);
                $oRs->fetchRow();
            }

            $sQ = "delete from oxcache where oxexpire > " . time() . " and ( $sResetConditions ) ";
            $masterDb->execute($sQ);
        }
    }

    /**
     * Returns the total size of the cached entries in db
     *
     * @param bool $blExpired flag, whether expired entries are included
     *
     * @return int
     */
    public function getTotalCacheSize($blExpired = false)
    {
        $sShopID = $this->getConfig()->getShopId();
        $sSelect = "select sum(oxsize) from `oxcache` where oxexpire ";
        $sSelect .= ($blExpired) ? " <= " : " > ";
        $sSelect .= ":oxexpire and oxshopid = :oxshopid";

        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        return \OxidEsales\Eshop\Core\DatabaseProvider::getMaster()->getOne($sSelect, [
            ':oxexpire' => time(),
            ':oxshopid' => $sShopID
        ]);
    }

    /**
     * Returns the amount of cached entries in db
     *
     * @param bool $blExpired flag, whether expired entries are included
     *
     * @return int
     */
    public function getTotalCacheCount($blExpired = false)
    {
        $sShopID = $this->getConfig()->getShopId();
        $sSelect = "select count(oxid) from `oxcache` where oxexpire";
        $sSelect .= ($blExpired) ? " <= " : " > ";
        $sSelect .= ":oxexpire and oxshopid = :oxshopid";

        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        return \OxidEsales\Eshop\Core\DatabaseProvider::getMaster()->getOne($sSelect, [
            ':oxexpire' => time(),
            ':oxshopid' => $sShopID
        ]);
    }

    /**
     * Returns the total hit count of all entries in db
     *
     * @param bool $blExpired flag, whether expired entries are included
     *
     * @return int
     */
    public function getTotalCacheHits($blExpired = false)
    {
        $sShopID = $this->getConfig()->getShopId();

        $sSelect = "select sum(oxhits) from `oxcache` where oxexpire";
        $sSelect .= ($blExpired) ? " <= " : " > ";
        $sSelect .= ":oxexpire and oxshopid = :oxshopid";

        // We force reading from master to prevent issues with slow replications or open transactions (see ESDEV-3804).
        return \OxidEsales\Eshop\Core\DatabaseProvider::getMaster()->getOne($sSelect, [
            ':oxexpire' => time(),
            ':oxshopid' => $sShopID
        ]);
    }

    /**
     * Callback function which processes dynamic parts of html. If all needed data is
     * set and processed correctly new generated html code is returned. On case of
     * missing template to render - initial dada is returned
     *
     * @param array $aMathes regular expression matchers
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "processDynContent" in next major
     */
    protected function _processDynContent($aMathes) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $viewData = [];
        $viewData['_render4cache'] = ['0'];


        $aDynParams = array();
        $aAllParts = explode(' ', trim($aMathes[1]));

        // processing dyn params
        foreach ($aAllParts as $sOnePart) {
            list ($sName, $sValue) = explode('=', $sOnePart);

            $sName = trim($sName);
            if ($sName) {
                $aDynParams[$sName] = base64_decode(trim($sValue));
            }
        }

        if (isset($aDynParams['type'])) {
            if (isset($aDynParams['aid']) && $aDynParams['aid']) {
                // Compare links
                if ($aDynParams['type'] == 'compare') {
                    $aCompareItems = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('aFiltcompproducts');

                    $viewData['_compare_aid'] = $aDynParams['aid'];

                    if (isset($aCompareItems[$aDynParams['aid']])) {
                        $viewData['_compare_in_list'] = true;
                    } else {
                        $viewData['_compare_in_list'] = false;
                    }
                    unset($aDynParams['aid']);
                    unset($aDynParams['in_list']);
                }
            }
        }

        //Assign auto parameters
        $sPrefix = '_';
        if (isset($aDynParams['type'])) {
            $sPrefix .= $aDynParams['type'] . '_';
        }

        foreach ($aDynParams as $sKey => $sVal) {
            if ($sKey != 'type' && $sKey != 'file') {
                $viewData[$sPrefix . $sKey] = $sVal;
            }
        }

        if (isset($aDynParams['file']) && $aDynParams['file']) {
            $sRes = $this->getRenderer()->renderTemplate($aDynParams['file'], $viewData);

            return $sRes;
        }

        // returning unchanged
        return $aMathes[0];
    }

    /**
     * @internal
     *
     * @return TemplateRendererInterface
     */
    private function getRenderer()
    {
        return $this->getContainer()
            ->get(TemplateRendererBridgeInterface::class)
            ->getTemplateRenderer();
    }

    /**
     * Searches for URL's and adds force_sid placeholder where they're missing
     *
     * @param string $sContent conten to cache
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "appendSidPlaceholder" in next major
     */
    protected function _appendSidPlaceholder($sContent) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $aUrls = array();
        $aUpdatedUrls = array();

        $sShopUrl = $this->getConfig()->getShopUrl();
        $sShopSslUrl = $this->getConfig()->getSslShopUrl();

        if (!empty($sShopSslUrl) && $sShopUrl != $sShopSslUrl) {
            $sShopUrlPattern = "(" . preg_quote($sShopUrl, "@") . "|" . preg_quote($sShopSslUrl, "@") . ")";
        } else {
            $sShopUrlPattern = "(" . preg_quote($sShopUrl, "@") . ")";
        }

        // regexp pattern for urls in anchors
        $sHrefPattern = '@(<a.*href=")(' . $sShopUrlPattern . '[^"#]*)(#|")@i';

        // getting all urls
        if (preg_match_all($sHrefPattern, $sContent, $aUrls)) {
            foreach ($aUrls[2] as $sKey => $sUrl) {
                // removing force_sid=... if there is one
                $sUrl = \OxidEsales\Eshop\Core\Registry::getUtilsUrl()->cleanUrl($sUrl, array("force_sid"));

                // removing parameter separator from url end
                $sUrl = preg_replace("/(\?|&(amp;)?)$/", "", $sUrl);

                // adding force_sid placeholder to url end
                if (strstr($sUrl, "?")) {
                    // there are already some parameters - using placeholder with &amp;
                    $sUrl .= self::SESSION_FULL_ID_AMP_PLACEHOLDER;
                } else {
                    // no parameters - using placeholder with ?
                    $sUrl .= self::SESSION_FULL_ID_QUE_PLACEHOLDER;
                }

                // formating full updated html <a> tag
                $aUpdatedUrls[] = $aUrls[1][$sKey] . $sUrl . $aUrls[4][$sKey];
            }

            $sContent = str_replace($aUrls[0], $aUpdatedUrls, $sContent);
        }

        return $sContent;
    }

    /**
     * remove user sensitive data for caching content
     *
     * @param string $sContent content to cache
     *
     * @return string
     * @deprecated underscore prefix violates PSR12, will be renamed to "cleanSensitiveData" in next major
     */
    protected function _cleanSensitiveData($sContent) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sSid = $this->getSession()->getId();
        if ($sSid && $sSid != 'x') {
            // adding a force_sid placeholder (if needed)
            $sContent = $this->_appendSidPlaceholder($sContent);

            // searching for already defined sids and stokes and adding sid placeholders
            $aSearch = array();
            $aReplace = array();

            // searching for SID in other places (input values and so on..)
            $aSearch[] = '#(^|[^a-zA-Z0-9])' . preg_quote($sSid, '#') . '([^a-zA-Z0-9]|$)#';
            $aReplace[] = '\1' . self::SESSION_ID_PLACEHOLDER . '\2';

            if (($sToken = \OxidEsales\Eshop\Core\Registry::getSession()->getVariable('sess_stoken'))) {
                $aSearch[] = '#(^|[^a-zA-Z0-9])' . preg_quote($sToken, '#') . '([^a-zA-Z0-9]|$)#';
                $aReplace[] = '\1' . self::SESSION_STOKEN_PLACEHOLDER . '\2';
            }

            return preg_replace($aSearch, $aReplace, $sContent);
        }

        return $sContent;
    }

    /**
     * Renders dynamic contents within cached contents.
     *
     * @param string $sOutput static contents
     *
     * @return string
     */
    public function processCache($sOutput)
    {
        $mySession = $this->getSession();

        $sSid = $mySession->getId();
        $sToken = '';
        $sForceSid = '';
        $sForceSidAmp = '';
        $sForceSidQue = '';
        if ($sSid != '') {
            $sToken = $mySession->getSessionChallengeToken();
        }
        if ($sSid && $mySession->isSidNeeded()) {
            $sForceSid = "force_sid=$sSid";
            $sForceSidAmp = "&amp;force_sid=$sSid";
            $sForceSidQue = "?force_sid=$sSid";
        }

        // replacing cleared data
        $sOutput = str_replace(
            array(
                 self::SESSION_ID_PLACEHOLDER,
                 self::SESSION_STOKEN_PLACEHOLDER,
                 self::SESSION_FULL_ID_PLACEHOLDER,
                 self::SESSION_FULL_ID_AMP_PLACEHOLDER,
                 self::SESSION_FULL_ID_QUE_PLACEHOLDER
            ),
            array(
                 $sSid,
                 $sToken,
                 $sForceSid,
                 $sForceSidAmp,
                 $sForceSidQue
            ),
            $sOutput
        );

        return preg_replace_callback("/<oxid_dynamic>(.*)<\/oxid_dynamic>/mUs", array($this, '_processDynContent'), $sOutput);
    }

    /**
     * Adds an entry to the db oxcache table.
     *
     * @param string $sCacheId an id to be added to cache
     * @param int    $iSize    the length of content to be added
     * @param string $sResetOn a reset (date?)
     * @deprecated underscore prefix violates PSR12, will be renamed to "addId" in next major
     */
    protected function _addId($sCacheId, $iSize = 0, $sResetOn = '') // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        $values = [
            'oxid'      => md5($sCacheId),
            'expire'    => time() + (int) $this->getCacheLifeTime(),
            'reseton'   => $sResetOn,
            'cachesize' => $iSize,
            'hits'      => 0,
            'shopid'    => $this->getConfig()->getShopId()
        ];

        $query = "INSERT INTO `oxcache` ( `oxid`, `oxexpire`, `oxreseton`, `oxsize`, `oxhits`, `oxshopid` ) " .
                 " VALUES( :oxid, :expire, :reseton, :cachesize, :hits, :shopid ) " .
                 " ON DUPLICATE KEY UPDATE " .
                 " `oxexpire` = :expire, " .
                 " `oxreseton` = :reseton, " .
                 " `oxsize` = :cachesize, " .
                 " `oxhits` = :hits, " .
                 " `oxshopid` = :shopid ";

        $database->execute($query, $values);
    }

    /**
     * Increments the hit counter in the db entry,  when a certain id is retrieved from cache.
     * This method as inserting to the table on every call decreases caching efficiency.
     * However this functionality could be enabled using "blShowCacheHits" config option if needed.
     *
     * @param string $sCacheId an id of the cache entry, whose hit counter is to be incremented
     * @deprecated underscore prefix violates PSR12, will be renamed to "addHit" in next major
     */
    protected function _addHit($sCacheId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        //if ($this->getConfig()->getParam("blShowCacheHits")) {
        if (!$this->getConfig()->getActiveShop()->oxshops__oxproductive->value) {
            $sShopID = $this->getConfig()->getShopId();
            $sId = md5($sCacheId);

            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("update `oxcache` set `oxhits` = `oxhits`+1 where oxid = :oxid and oxshopid = :oxshopid", [
                ':oxid' => $sId,
                ':oxshopid' => $sShopID
            ]);
        }
    }

    /**
     * Removes an id from db oxcache table
     *
     * @param string $sCacheId identifier of the entry to be removed
     * @deprecated underscore prefix violates PSR12, will be renamed to "removeId" in next major
     */
    protected function _removeId($sCacheId) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sShopID = $this->getConfig()->getShopId();
        $sId = md5($sCacheId);
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->execute("delete from `oxcache` where oxid = :oxid and oxshopid = :oxshopid", [
            ':oxid' => $sId,
            ':oxshopid' => $sShopID
        ]);
    }
}
