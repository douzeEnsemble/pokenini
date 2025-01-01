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

    /**
     * @return string[][]
     */
    public function getTopN(
        string $trainerExternalId,
        string $electionSlug,
        int $count,
    ): array {
        $sql = <<<'SQL'
            SELECT  tpe.elo AS elo,
                    p.slug AS pokemon_slug,
                    p.name AS pokemon_name,
                    p.national_dex_number AS pokemon_national_dex_number,
                    p.simplified_name AS pokemon_simplified_name,
                    p.forms_label AS pokemon_forms_label,
                    p.french_name AS pokemon_french_name,
                    p.simplified_french_name AS pokemon_simplified_french_name,
                    p.forms_french_label AS pokemon_forms_french_label,
                    p.icon_name AS pokemon_icon,
                    p.family_order AS pokemon_family_order,
                    pp.slug AS family_lead_slug,
                    cf.slug as category_form_slug,
                    cf.name as category_form_name,
                    rf.slug as regional_form_slug,
                    rf.name as regional_form_name,
                    sf.slug as special_form_slug,
                    sf.name as special_form_name,
                    vf.slug as variant_form_slug,
                    vf.name as variant_form_name,
                    pt.slug AS primary_type_slug,
                    pt.name AS primary_type_name,
                    pt.french_name AS primary_type_french_name,
                    st.slug AS secondary_type_slug,
                    st.name AS secondary_type_name,
                    st.french_name AS secondary_type_french_name,
                    ogb.slug AS original_game_bundle_slug,
                    CONCAT(
                        '999',
                        '-',
                        LPAD(CAST(p.national_dex_number AS varchar), 4, '0'),
                        '-',
                        LPAD(CAST(p.family_order AS varchar), 3, '0')
                    ) as pokemon_order_number
            FROM    trainer_pokemon_elo AS tpe
                JOIN pokemon AS p
                    ON tpe.pokemon_id = p.id
                LEFT JOIN category_form AS cf
                    ON p.category_form_id = cf.id
                LEFT JOIN regional_form AS rf
                    ON p.regional_form_id = rf.id
                LEFT JOIN special_form AS sf
                    ON p.special_form_id = sf.id
                LEFT JOIN variant_form AS vf
                    ON p.variant_form_id = vf.id
                LEFT JOIN "type" AS pt
                    ON p.primary_type_id = pt.id
                LEFT JOIN "type" AS st
                    ON p.secondary_type_id = st.id
                LEFT JOIN pokemon AS pp
                    ON p.family = pp.slug
                LEFT JOIN game_bundle AS ogb
                    ON p.original_game_bundle_id = ogb.id
            WHERE   tpe.trainer_external_id = :trainer_external_id
                AND tpe.election_slug = :election_slug
            ORDER BY tpe.elo DESC
            LIMIT   :count
            SQL;

        $params = [
            'trainer_external_id' => $trainerExternalId,
            'election_slug' => $electionSlug,
            'count' => $count,
        ];

        $types = [
            'trainer_external_id' => ParameterType::STRING,
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
}
