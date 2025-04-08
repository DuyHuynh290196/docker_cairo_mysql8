<?php
/**
 * Adds languages to shop, if needed also adds new fields.
 * After this script you need to update views.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Multishop\Helpers;

use Exception;
use oxDb;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class LanguageInsertor
 */
class LanguageInsertor
{
    /**
     * @var $iLanguageCount
     */
    private $_iLanguageCount;

    /**
     * Constructor
     *
     * @param int $iLanguageCount language count
     */
    public function __construct($iLanguageCount = 8)
    {
        $this->setLanguageCount($iLanguageCount);
    }

    /**
     * Set language count
     *
     * @param int $iLanguageCount language count to generate
     *
     * @return null
     */
    public function setLanguageCount($iLanguageCount)
    {
        $this->_iLanguageCount = $iLanguageCount;
    }

    /**
     * Get language count for generation
     *
     * @return int
     */
    public function getLanguageCount()
    {
        return (int) $this->_iLanguageCount;
    }

    /**
     * Check if selected language already has multilanguage fields in DB
     *
     * @param int $iBaseId language abbreviation
     *
     * @return bool
     */
    public function checkMultilangFieldsExistsInDb($iBaseId)
    {
        $sTable = getLangTableName('oxarticles', $iBaseId);
        $sColumn = 'oxtitle' . Registry::getLang()->getLanguageTag($iBaseId);

        $oDbMetadata = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        return $oDbMetadata->tableExists($sTable) && $oDbMetadata->fieldExists($sColumn, $sTable);
    }

    /**
     * Adding new language to DB - creating new multilanguage fields with new
     * language ID (e.g. oxtitle_4)
     *
     * @return null
     */
    public function addNewMultilangFieldsToDb()
    {
        //creating new multilanguage fields with new id over whole DB
        \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->startTransaction();

        $oDbMeta = oxNew(\OxidEsales\Eshop\Core\DbMetaDataHandler::class);

        try {
            $oDbMeta->addNewLangToDb();
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->commitTransaction();
        } catch (Exception $oEx) {
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->rollbackTransaction();

            exit(1);
        }
    }

    /**
     * Adds new languages and makes them active
     * After this script update views
     *
     * @return null
     */
    public function start()
    {
        $oxConfig = Registry::getConfig();
        $aLangData['params'] = $oxConfig->getConfigParam('aLanguageParams');
        $aLangData['lang'] = $oxConfig->getConfigParam('aLanguages');

        $iLanguageCount = $this->getLanguageCount();
        $iCurrentLangCount = count($aLangData['lang']);
        for ($i = $iCurrentLangCount + 1; $i <= $iLanguageCount; $i++) {
            $sOxId = 'L' . $i;
            $aLangData['params'][$sOxId]['baseId'] = $i - 1;
            $aLangData['params'][$sOxId]['active'] = 1;
            $aLangData['params'][$sOxId]['default'] = 0;
            $aLangData['params'][$sOxId]['sort'] = $i;

            $aLangData['lang'][$sOxId] = 'Language' . $i;
            //saving languages info
            $oxConfig->saveShopConfVar('aarr', 'aLanguageParams', $aLangData['params']);
            $oxConfig->saveShopConfVar('aarr', 'aLanguages', $aLangData['lang']);

            //checking if added language already has created multilang fields
            //with new base ID - if not, creating new fields
            if (!$this->checkMultilangFieldsExistsInDb($i - 1)) {
                $this->addNewMultilangFieldsToDb();
            }
        }
    }
}
