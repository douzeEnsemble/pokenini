<?php

declare(strict_types=1);

namespace App\Tests\Api\Common\Traits\CounterTrait;

use Doctrine\DBAL\Connection;

trait CountPokemonAvailabilitiesTrait
{
    protected function getPokemonAvailabilitiesCount(string $category): int
    {
        /** @var Connection $connection */
        $connection = static::getContainer()->get(Connection::class);

        /** @var int */
        return $connection->executeQuery(
            "SELECT COUNT(*) FROM pokemon_availabilities WHERE category = '{$category}'"
        )->fetchOne();
    }
}
