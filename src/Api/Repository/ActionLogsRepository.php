<?php

declare(strict_types=1);

namespace App\Api\Repository;

use App\Api\Entity\ActionLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActionLog>
 */
class ActionLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionLog::class);
    }

    /**
     * @return string[][]|null[][]
     */
    public function getLastests(): array
    {
        $sql = <<<SQL
        SELECT
            TRIM(BOTH '_' FROM
                LOWER(
                    regexp_replace(
                        REPLACE("type", 'App\Api\Message\', ''),
                        '([A-Z])',
                        '_\\1',
                        'g'
                    )
                )
            ) as type_action,
            t.rn as row_number,
            created_at AT TIME ZONE 'UTC' AS created_at,
            done_at AT TIME ZONE 'UTC' AS done_at,
            EXTRACT(EPOCH FROM (done_at - created_at)) AS execution_time,
            details,
            error_trace
        FROM    (
                SELECT  "type",
                        created_at,
                        done_at,
                        report_data as details,
                        error_trace as error_trace,
                        row_number() OVER(
                            PARTITION BY "type"
                            ORDER BY created_at DESC
                        ) AS rn
                FROM    action_log
            ) t
        WHERE t.rn BETWEEN 1 AND 2

        ORDER BY    "type" ASC, created_at DESC
        SQL;

        /** @var string[][]|null[][] */
        return $this->getEntityManager()->getConnection()->fetchAllAssociative($sql);
    }
}
