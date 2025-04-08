<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopEnterprise\Tests\Integration\Internal\Framework\Console;

use OxidEsales\Eshop\Core\DbMetaDataHandler;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\ContainerTrait;

/**
 * @internal
 */
trait SubShopManagerTrait
{
    use ContainerTrait;

    private function createSubshopEntry()
    {
        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
        $queryBuilderFactory = $this->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder
            ->insert('oxshops')
            ->values(
                [
                    'oxid' => '?',
                ]
            )
            ->setParameter(0, 2)
            ->execute();
        Registry::getConfig()->saveShopConfVar('arr', 'aLanguages', [0=>1], 2);
        $dataHandler = oxNew(DbMetaDataHandler::class);
        $dataHandler->updateViews();
    }

    private function deleteSubshopEntry()
    {
        /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
        $queryBuilderFactory = $this->get(QueryBuilderFactoryInterface::class);
        $queryBuilder = $queryBuilderFactory->create();
        $queryBuilder
            ->delete('oxshops')
            ->where('oxid = :id')
            ->setParameter('id', 2)
            ->execute();
        $queryBuilder
            ->delete('oxconfig')
            ->where('oxshopid = :oxshopid')
            ->andWhere('oxvarname LIKE :variableName')
            ->setParameter('oxshopid', 2)
            ->setParameter('variableName', 'aLanguages')
            ->execute();
    }
}
