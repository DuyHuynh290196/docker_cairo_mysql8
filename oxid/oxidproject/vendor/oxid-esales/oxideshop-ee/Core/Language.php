<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * @inheritdoc
 */
class Language extends \OxidEsales\EshopProfessional\Core\Language
{
    /**
     * @inheritdoc
     */
    protected function getCustomThemeLanguageFiles($language)
    {
        $config = $this->getConfig();
        $theme = $config->getConfigParam("sTheme");
        $customTheme = $config->getConfigParam("sCustomTheme");
        $shopId = $config->getShopId();
        $applicationDirectory = $config->getAppDir();
        $languageAbbreviation = \OxidEsales\Eshop\Core\Registry::getLang()->getLanguageAbbr($language);

        $languageFiles = parent::getCustomThemeLanguageFiles($language);

        if ($customTheme) {
            $shopPath = $applicationDirectory . 'views/' . $customTheme . '/' . $shopId . '/' . $languageAbbreviation;
            $languageFiles[] = $shopPath . "/lang.php";
            $languageFiles = $this->_appendLangFile($languageFiles, $shopPath);
        } elseif ($theme) {
            // theme shop languages
            $shopPath = $applicationDirectory . 'views/' . $theme . '/' . $shopId . '/' . $languageAbbreviation;
            $languageFiles[] = $shopPath . "/lang.php";
            $languageFiles = $this->_appendLangFile($languageFiles, $shopPath);
        }

        return $languageFiles;
    }

    /**
     * @inheritdoc
     */
    public function getMultiLangTables()
    {
        $tables = parent::getMultiLangTables();
        $tables[] = "oxfield2shop";

        return $tables;
    }

    /**
     * @inheritdoc
     * @deprecated underscore prefix violates PSR12, will be renamed to "getLanguageIdsFromDatabase" in next major
     */
    protected function _getLanguageIdsFromDatabase($shopId = null) // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $languages = $this->_getConfigLanguageValues('aLanguageParams', $shopId);

        if (empty($languages)) {
            $languages = $this->_getConfigLanguageValues('aLanguages', $shopId);
        }

        return $languages;
    }
}
