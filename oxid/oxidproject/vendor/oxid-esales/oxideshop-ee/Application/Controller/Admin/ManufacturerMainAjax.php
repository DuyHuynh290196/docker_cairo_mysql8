<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Application\Controller\Admin;

/**
 * @inheritdoc
 */
class ManufacturerMainAjax extends \OxidEsales\EshopProfessional\Application\Controller\Admin\ManufacturerMainAjax
{
    /**
     * @inheritdoc
     */
    protected function formManufacturerRemovalQuery($articlesToRemove)
    {
        $query = parent::formManufacturerRemovalQuery($articlesToRemove);
        $query .= " and oxshopid='" . $this->getConfig()->getShopId() . "' ";

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function removeManufacturer()
    {
        parent::removeManufacturer();
        $manufacturerId = $this->getConfig()->getRequestParameter('oxid');

        if ($manufacturerId && $manufacturerId != "-1") {
            $manufacturer = oxNew(\OxidEsales\Eshop\Application\Model\Manufacturer::class);
            $manufacturer->load($manufacturerId);
            $manufacturer->executeDependencyEvent();
        }
    }

    /**
     * @inheritdoc
     */
    public function addManufacturer()
    {
        parent::addManufacturer();

        $manufacturerId = $this->getConfig()->getRequestParameter('synchoxid');

        if ($manufacturerId && $manufacturerId != "-1") {
            $manufacturer = oxNew(\OxidEsales\Eshop\Application\Model\Manufacturer::class);
            $manufacturer->load($manufacturerId);
            $manufacturer->executeDependencyEvent();
        }
    }

    /**
     * @inheritdoc
     */
    protected function formArticleToManufacturerAdditionQuery($manufacturerId, $articlesToAdd)
    {
        $query = parent::formArticleToManufacturerAdditionQuery($manufacturerId, $articlesToAdd);
        $query .= " and oxshopid='" . $this->getConfig()->getShopId() . "' ";

        return $query;
    }
}
