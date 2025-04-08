<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Core\GenericImport\ImportObject;

use Exception;
use oxBase;
use OxidEsales\Eshop\Core\GenericImport\GenericImport;

/**
 * Import object for Order Articles.
 */
class OrderArticle extends \OxidEsales\EshopProfessional\Core\GenericImport\ImportObject\OrderArticle
{
    /** @var string Database table name. */
    protected $tableName = 'oxorderarticles';

    /** @var string Shop object name. */
    protected $shopObjectName = 'oxorderarticle';

    /**
     * Check for write access for id.
     *
     * @param \OxidEsales\Eshop\Core\Model\BaseModel $shopObject Loaded shop object
     * @param array  $data       Fields to be written, null for default
     *
     * @throws Exception on now access
     */
    public function checkWriteAccess($shopObject, $data = null)
    {
        if ($shopObject->oxorderarticles__oxordershopid->value != \OxidEsales\Eshop\Core\Registry::getConfig()->getShopId()) {
            throw new Exception(GenericImport::ERROR_USER_NO_RIGHTS);
        }

        parent::checkWriteAccess($shopObject, $data);
    }

    /**
     * Returns formed order shop id, which should be set to data array.
     *
     * @param string $currentShopId
     *
     * @return string
     */
    protected function getOrderShopId($currentShopId)
    {
        return $currentShopId;
    }
}
