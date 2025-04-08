<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core;

/**
 * Debug information formatter.
 */
class DebugInfo extends \OxidEsales\EshopProfessional\Core\DebugInfo
{
    /**
     * format cache info
     *
     * @param boolean $blIsCache  can cache
     * @param boolean $blIsCached is taken from cache
     * @param string  $sViewID    view id
     *
     * @return string
     */
    public function formatContentCaching($blIsCache = false, $blIsCached = false, $sViewID = null)
    {
        $sLog = '';
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getConfigParam('blUseContentCaching')) {
            $sLog .= 'Content cache : ';

            if ($blIsCache) {
                if ($blIsCached) {
                    $sLog .= ' Cache Hit ';
                } else {
                    $sLog .= ' Cache Miss ';
                }

                $sLog .= ' [' . $sViewID . '] ';
            } else {
                $sLog .= ' Not Cachable ';
            }
            $sLog .= '<br />';
        }

        return $sLog;
    }
}
