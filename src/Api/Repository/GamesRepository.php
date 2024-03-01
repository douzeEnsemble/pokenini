<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @return string[]
     */
    public function getAllSlugs(): array
    {
        $queryBuilder = $this->createQueryBuilder('g');
        $queryBuilder->select('g.slug');
        $queryBuilder->orderBy('g.orderNumber');

        /** @var string[] */
        return $queryBuilder->getQuery()->getSingleColumnResult();
    }
}
