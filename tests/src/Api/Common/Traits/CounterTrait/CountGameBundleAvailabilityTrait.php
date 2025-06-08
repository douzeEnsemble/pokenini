<?php

declare(strict_types=1);

namespace App\Tests\Api\Common\Traits\CounterTrait;

use Doctrine\DBAL\Connection;

trait CountGameBundleAvailabilityTrait
{
    protected function getGameBundleAvailabilityCount(): int
    {
        /** @var Connection $connection */
        $connection = static::getContainer()->get(Connection::class);

        /** @var int */
        return $connection->executeQuery('SELECT COUNT(*) FROM game_bundle_availability')->fetchOne();
    }
}
