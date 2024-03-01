<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Region>
 */
class RegionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    /**
     * @return string[]
     */
    public function getAllSlugs(): array
    {
        $queryBuilder = $this->createQueryBuilder('r');
        $queryBuilder->select('r.slug');
        $queryBuilder->orderBy('r.orderNumber');

        /** @var string[] */
        return $queryBuilder->getQuery()->getSingleColumnResult();
    }
}
