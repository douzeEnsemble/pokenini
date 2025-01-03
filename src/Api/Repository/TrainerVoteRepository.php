<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\TrainerVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\ParameterType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainerVote>
 */
class TrainerVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainerVote::class);
    }

    /**
     * @param string[] $winners
     * @param string[] $losers
     */
    public function register(
        string $trainerExternalId,
        string $electionSlug,
        array $winners,
        array $losers,
    ): void {
        $sql = <<<'SQL'
            INSERT INTO trainer_vote (
                id,
                trainer_external_id,
                election_slug,
                winners,
                losers,
                created_at
            ) VALUES (
                gen_random_uuid(),
                :trainer_external_id,
                :election_slug,
                :winners,
                :losers,
                NOW()
            )
            SQL;

        $params = [
            'trainer_external_id' => $trainerExternalId,
            'election_slug' => $electionSlug,
            'winners' => json_encode($winners, JSON_THROW_ON_ERROR, 1),
            'losers' => json_encode($losers, JSON_THROW_ON_ERROR, 1),
        ];

        $types = [
            'trainer_external_id' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
            'winners' => ParameterType::STRING,
            'losers' => ParameterType::STRING,
        ];

        $this->getEntityManager()->getConnection()->executeQuery(
            $sql,
            $params,
            $types,
        );
    }

    public function getCount(
        string $trainerExternalId,
        string $electionSlug,
    ): int {
        $sql = <<<'SQL'
            SELECT  COUNT(*) AS count
            FROM    trainer_vote AS tv
            WHERE   tv.trainer_external_id = :trainer_external_id
                AND tv.election_slug = :election_slug
            SQL;

        $params = [
            'trainer_external_id' => $trainerExternalId,
            'election_slug' => $electionSlug,
        ];

        $types = [
            'trainer_external_id' => ParameterType::STRING,
            'election_slug' => ParameterType::STRING,
        ];

        /** @var int */
        return $this->getEntityManager()->getConnection()->fetchOne(
            $sql,
            $params,
            $types,
        );
    }
}
