<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokemon>
 */
class PokemonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function removeAll(): void
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->update()
            ->set('p.deletedAt', ':now')
            ->setParameter('now', new \DateTimeImmutable())
        ;

        $queryBuilder->getQuery()->execute();
    }

    public function getQueryAll(): AbstractQuery
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->where($queryBuilder->expr()->isNull('p.deletedAt'));
        $queryBuilder->orderBy('p.nationalDexNumber, p.familyOrder');

        return $queryBuilder->getQuery();
    }

    public function countAll(): int
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->select($queryBuilder->expr()->count('p'));

        /** @var int */
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
