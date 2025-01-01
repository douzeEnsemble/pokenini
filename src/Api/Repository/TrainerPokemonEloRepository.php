<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\TrainerPokemonElo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainerPokemonElo>
 */
class TrainerPokemonEloRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainerPokemonElo::class);
    }

    public function getElo(
        string $trainerExternalId,
        string $electionSlug,
        string $pokemonSlug,
    ): int {
        $sql = <<<'SQL'
            SELECT  tpe.elo
            FROM    trainer_pokemon_elo AS tpe
                JOIN pokemon AS p
                    ON tpe.pokemon_id = p.id AND p.slug = :pokemon_slug
            WHERE   tpe.trainer_external_id = :trainer_external_id
                AND tpe.election_slug = :election_slug
            SQL;

        $params = [
            'trainer_external_id' => $trainerExternalId,
            'election_slug' => $electionSlug,
            'pokemon_slug' => $pokemonSlug,
        ];

        $types = [
            'trainer_external_id' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
            'pokemon_slug' => ParameterType::STRING,
        ];

        /** @var string */
        $result = $this->getEntityManager()->getConnection()->fetchOne(
            $sql,
            $params,
            $types,
        );

        return (int) $result;
    }

    public function updateElo(
        int $elo,
        string $trainerExternalId,
        string $electionSlug,
        string $pokemonSlug,
    ): void {
        $sql = <<<'SQL'
            UPDATE  trainer_pokemon_elo AS tpe
            SET     elo = :elo
            FROM    pokemon AS p
            WHERE   tpe.pokemon_id = p.id AND p.slug = :pokemon_slug
                AND tpe.trainer_external_id = :trainer_external_id
                AND tpe.election_slug = :election_slug
            SQL;

        $params = [
            'elo' => $elo,
            'trainer_external_id' => $trainerExternalId,
            'election_slug' => $electionSlug,
            'pokemon_slug' => $pokemonSlug,
        ];

        $types = [
            'elo' => ParameterType::INTEGER,
            'trainer_external_id' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
            'pokemon_slug' => ParameterType::STRING,
        ];

        $this->getEntityManager()->getConnection()->executeQuery(
            $sql,
            $params,
            $types,
        );
    }
}
