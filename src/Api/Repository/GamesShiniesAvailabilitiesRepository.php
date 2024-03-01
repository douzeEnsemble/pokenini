<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\DTO\GamesShiniesAvailabilities;
use App\Api\Entity\GameShinyAvailability;
use App\Api\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameShinyAvailability>
 */
class GamesShiniesAvailabilitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameShinyAvailability::class);
    }

    public function removeAll(): void
    {
        $queryBuilder = $this->createQueryBuilder('gsa')
            ->delete()
        ;

        $queryBuilder->getQuery()->execute();
    }

    /**
     * @param Pokemon $pokemon
     *
     * @return GamesShiniesAvailabilities dex slug as property and dex availability as value
     */
    public function getFromPokemon(Pokemon $pokemon): GamesShiniesAvailabilities
    {
        $queryBuilder = $this->createQueryBuilder('gsa');

        $queryBuilder->select('gsa.availability');
        $queryBuilder->addSelect('g.slug');

        $queryBuilder->join('gsa.game', 'g');

        $queryBuilder->where($queryBuilder->expr()->eq('gsa.pokemonSlug', ':pokemonSlug'));
        $queryBuilder->setParameter('pokemonSlug', $pokemon->slug);

        $queryBuilder->orderBy('g.name');

        /** @var string[][] $result */
        $result = $queryBuilder->getQuery()->getArrayResult();

        $list = [];
        foreach ($result as $line) {
            /** @var bool $isAvailable */
            $isAvailable =  (
                '—' !== $line['availability']
                && '-' !== $line['availability']
                && '' !== $line['availability']
            );

            $list[$line['slug']] = $isAvailable;
        }

        return new GamesShiniesAvailabilities($list);
    }
}
