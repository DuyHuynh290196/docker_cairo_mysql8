<?php

namespace Makaira\OxidConnectEssential\Controller\Admin;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\ParameterType;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use Makaira\OxidConnectEssential\Domain\Revision;
use Makaira\OxidConnectEssential\Entity\RevisionRepository;
use Makaira\OxidConnectEssential\SymfonyContainerTrait;
use OxidEsales\Eshop\Core\Registry;
use Psr\Container\ContainerInterface;
use Psr\Container;
use Doctrine\DBAL;

use function array_map;
use function implode;

/**
 * @SuppressWarnings(PHPMD.ElseExpression)
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class SelectListMainAjax extends SelectListMainAjax_parent
{
    use PSR12WrapperTrait;
    use SymfonyContainerTrait;

    /**
     * @return void
     * @throws Container\ContainerExceptionInterface
     * @throws Container\NotFoundExceptionInterface
     * @throws DBAL\ConnectionException
     * @throws DBAL\Driver\Exception
     * @throws DBAL\Exception
     */
    public function removeArtFromSel(): void
    {
        $container = $this->getSymfonyContainer();

        /** @var QueryBuilderFactoryInterface $queryBuilder */
        $queryBuilder = $this->getSymfonyContainer()->get(QueryBuilderFactoryInterface::class);
        $connection   = $queryBuilder->create()
            ->getConnection();

        /** @var string $articleSelectListView */
        $articleSelectListView = $this->callPSR12Incompatible('_getViewName', 'oxobject2selectlist');

        /** @var string $productView */
        $productView = $this->callPSR12Incompatible('_getViewName', 'oxarticles');

        if (Registry::getRequest()->getRequestParameter('all')) {
            /** @var string $oxidQuery */
            $oxidQuery = $this->callPSR12Incompatible('_getQuery');
            $query     = "SELECT {$articleSelectListView}.`OXOBJECTID`, {$productView}.`OXPARENTID` {$oxidQuery}";

            /** @var Result $resultStatement */
            $resultStatement = $connection->executeQuery($query);

            $changedProducts = $resultStatement->fetchAllAssociative();
        } else {
            $entryIds = (array) $this->callPSR12Incompatible('_getActionIds', 'oxobject2attribute.oxid');
            if (!empty($entryIds)) {
                $sqlEntryIds = implode(
                    ', ',
                    array_map(
                        static fn($entryId) => $connection->quote($entryId, ParameterType::STRING),
                        $entryIds
                    )
                );

                $query = "SELECT o2a.OXOBJECTID, a.OXPARENTID
                    FROM `{$articleSelectListView}` o2a
                    LEFT JOIN `{$productView}` a ON a.`OXID` = o2a.`OXOBJECTID`
                    WHERE o2a.`OXID` IN ({$sqlEntryIds})";

                /** @var Result $resultStatement */
                $resultStatement = $connection->executeQuery($query);
                $changedProducts  = $resultStatement->fetchAllAssociative();
            }
        }

        parent::removeArtFromSel();

        if (!empty($changedProducts)) {
            /** @var RevisionRepository $revisionRepository */
            $revisionRepository = $container->get(RevisionRepository::class);

            /**
             * @param array<string> $changedProduct
             *
             * @return Revision
             */
            $buildRevision = static fn(array $changedProduct) => new Revision(
                $changedProduct['OXPARENTID'] ? Revision::TYPE_VARIANT : Revision::TYPE_PRODUCT,
                $changedProduct['OXOBJECTID']
            );
            $revisionRepository->storeRevisions(
                array_map(
                    $buildRevision,
                    $changedProducts
                )
            );
        }
    }
}
