<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\TrainerPokemonElo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

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
        string $dexSlug,
        string $electionSlug,
        string $pokemonSlug,
    ): ?int {
        $sql = <<<'SQL'
            SELECT  tpe.elo
            FROM    trainer_pokemon_elo AS tpe
                JOIN pokemon AS p
                    ON tpe.pokemon_id = p.id AND p.slug = :pokemon_slug
            WHERE   tpe.trainer_external_id = :trainer_external_id
                AND tpe.dex_slug = :dex_slug
                AND tpe.election_slug = :election_slug
            SQL;

        $params = [
            'trainer_external_id' => $trainerExternalId,
            'dex_slug' => $dexSlug,
            'election_slug' => $electionSlug,
            'pokemon_slug' => $pokemonSlug,
        ];

        $types = [
            'trainer_external_id' => ParameterType::STRING,
            'dex_slug' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
            'pokemon_slug' => ParameterType::STRING,
        ];

        /** @var false|int */
        $result = $this->getEntityManager()->getConnection()->fetchOne(
            $sql,
            $params,
            $types,
        );

        return false === $result ? null : $result;
    }

    public function updateElo(
        int $elo,
        string $trainerExternalId,
        string $dexSlug,
        string $electionSlug,
        string $pokemonSlug,
        bool $isWinner,
    ): void {
        $sql = <<<'SQL'
            INSERT INTO trainer_pokemon_elo (
                id,
                trainer_external_id, 
                dex_slug, 
                election_slug, 
                pokemon_id, 
                elo,
                view_count,
                win_count
            )
            VALUES (
                :id,
                :trainer_external_id,
                :dex_slug,
                :election_slug,
                (SELECT id FROM pokemon WHERE slug = :pokemon_slug),
                :elo,
                1,
                CASE WHEN :is_winner THEN 1 ELSE 0 END
            )
            ON CONFLICT (trainer_external_id, dex_slug, election_slug, pokemon_id)
            DO
            UPDATE
            SET     elo = excluded.elo,
                    view_count = trainer_pokemon_elo.view_count + excluded.view_count,
                    win_count = trainer_pokemon_elo.win_count + excluded.win_count
            SQL;

        $params = [
            'id' => Uuid::v4(),
            'elo' => $elo,
            'trainer_external_id' => $trainerExternalId,
            'dex_slug' => $dexSlug,
            'election_slug' => $electionSlug,
            'pokemon_slug' => $pokemonSlug,
            'is_winner' => $isWinner,
        ];

        $types = [
            'id' => ParameterType::STRING,
            'elo' => ParameterType::INTEGER,
            'trainer_external_id' => ParameterType::STRING,
            'dex_slug' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
            'pokemon_slug' => ParameterType::STRING,
            'is_winner' => ParameterType::BOOLEAN,
        ];

        $this->getEntityManager()->getConnection()->executeQuery(
            $sql,
            $params,
            $types,
        );
    }

    /**
     * @return float[][]|int[][]|string[][]
     */
    public function getTopN(
        string $trainerExternalId,
        string $dexSlug,
        string $electionSlug,
        int $count,
    ): array {
        $sql = $this->getTopNSQL();

        $params = [
            'trainer_external_id' => $trainerExternalId,
            'dex_slug' => $dexSlug,
            'election_slug' => $electionSlug,
            'count' => $count,
        ];

        $types = [
            'trainer_external_id' => ParameterType::STRING,
            'dex_slug' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
            'count' => ParameterType::INTEGER,
        ];

        /** @var string[][] */
        return $this->getEntityManager()->getConnection()->fetchAllAssociative(
            $sql,
            $params,
            $types,
        );
    }

    /**
     * @return array{view_count_sum: int, win_count_sum: int, view_count_max: int, win_count_max: int, under_max_view_count: int, dex_total_count: int}
     */
    public function getMetrics(
        string $trainerExternalId,
        string $dexSlug,
        string $electionSlug,
    ): array {
        $sql = <<<'SQL'
            WITH stats AS (
                SELECT  MAX(view_count) AS max_view,
                        MAX(win_count) AS max_win
                FROM    trainer_pokemon_elo AS tpe
                WHERE   tpe.trainer_external_id = :trainer_external_id
                    AND tpe.dex_slug = :dex_slug
                    AND tpe.election_slug = :election_slug
            ), variables AS (
                SELECT  
                        COUNT(CASE WHEN tpe.view_count = s.max_view - 1 AND tpe.view_count = tpe.win_count THEN 1 END) AS under_max_view_count
                FROM    trainer_pokemon_elo AS tpe
                    CROSS JOIN stats s
                WHERE   tpe.trainer_external_id = :trainer_external_id
                    AND tpe.dex_slug = :dex_slug
                    AND tpe.election_slug = :election_slug
            )
            SELECT 
                SUM(view_count) AS view_count_sum,
                SUM(win_count) AS win_count_sum,
                (SELECT max_view FROM stats) AS view_count_max,
                (SELECT max_win FROM stats) AS win_count_max,
                (SELECT under_max_view_count FROM variables) AS under_max_view_count,
                (
                    SELECT  COUNT(1) AS count
                    FROM    dex_availability AS da
                        JOIN dex as d
                            ON da.dex_id = d.id AND d.slug = :dex_slug
                ) AS dex_total_count
            FROM    trainer_pokemon_elo AS tpe
            WHERE   tpe.trainer_external_id = :trainer_external_id
                    AND tpe.dex_slug = :dex_slug
                    AND tpe.election_slug = :election_slug
            SQL;

        $params = [
            'trainer_external_id' => $trainerExternalId,
            'dex_slug' => $dexSlug,
            'election_slug' => $electionSlug,
        ];

        $types = [
            'trainer_external_id' => ParameterType::STRING,
            'dex_slug' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
        ];

        /** @var int[]|string[] $result */
        $result = $this->getEntityManager()->getConnection()->fetchAssociative(
            $sql,
            $params,
            $types,
        );

        return [
            'view_count_sum' => $result['view_count_sum'] ?? 0,
            'win_count_sum' => $result['win_count_sum'] ?? 0,
            'view_count_max' => $result['view_count_max'] ?? 0,
            'win_count_max' => $result['win_count_max'] ?? 0,
            'under_max_view_count' => $result['under_max_view_count'] ?? 0,
            'dex_total_count' => $result['dex_total_count'] ?? 0,
        ];
    }

    private function getTopNSQL(): string
    {
        $sql = file_get_contents(dirname(__DIR__).'/../../resources/sql/trainer_pokemon_elo-get_top_n.sql');

        if (false === $sql) {
            // This condition is here form safety reason
            // It can never happen
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('Failed to read SQL file "trainer_pokemon_elo-get_top_n.sql"');
            // @codeCoverageIgnoreEnd
        }

        return $sql;
    }
}
