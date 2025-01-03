<?php

declare(strict_types=1);

namespace App\Tests\Api\Common\Traits\CounterTrait;

use Doctrine\DBAL\Connection;

trait CountTrainerVoteTrait
{
    protected function getTrainerVoteCount(string $trainerExternalId, string $electionSlug): int
    {
        /** @var Connection $connection */
        $connection = static::getContainer()->get(Connection::class);

        /** @var int */
        return $connection->executeQuery(
            "SELECT COUNT(*) FROM trainer_vote WHERE trainer_external_id = '{$trainerExternalId}' AND election_slug = '{$electionSlug}'"
        )->fetchOne();
    }
}
