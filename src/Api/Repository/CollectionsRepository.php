<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\Collection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collection>
 */
class CollectionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collection::class);
    }

    /**
     * @return string[]
     */
    public function getAllSlugs(): array
    {
        $queryBuilder = $this->createQueryBuilder('c');
        $queryBuilder->select('c.slug');
        $queryBuilder->where($queryBuilder->expr()->isNull('c.deletedAt'));
        $queryBuilder->orderBy('c.orderNumber');

        /** @var string[] */
        return $queryBuilder->getQuery()->getSingleColumnResult();
    }
}
