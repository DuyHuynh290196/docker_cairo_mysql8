<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\GenericImport\ImportObject;

use Exception;
use oxArticle;
use OxidEsales\Eshop\Core\GenericImport\GenericImport;

/**
 * Import object for Articles.
 */
class Article extends \OxidEsales\EshopProfessional\Core\GenericImport\ImportObject\Article
{
    /**
     * Basic access check for writing data. For oxArticle we allow super admin to change
     * subshop oxArticle fields described in config option aMultishopArticleFields.
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $shopObject Loaded shop object
     * @param array     $data       Fields to be written, null for default
     *
     * @throws Exception on now access
     */
    public function checkWriteAccess($shopObject, $data = null)
    {
        if (!$shopObject->canUpdate()) {
            throw new Exception(GenericImport::ERROR_USER_NO_RIGHTS);
        }
        unset($data['OXID']);
        foreach ($data as $field => $value) {
            if (!$shopObject->canUpdateField($field)) {
                throw new Exception(GenericImport::ERROR_USER_NO_RIGHTS);
            }
        }
    }
}
