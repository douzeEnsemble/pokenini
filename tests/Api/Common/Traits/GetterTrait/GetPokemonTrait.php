<?php

declare(strict_types=1);

namespace App\Tests\Api\Common\Traits\GetterTrait;

use Doctrine\DBAL\Connection;

trait GetPokemonTrait
{
    /**
     * @return string[]
     */
    protected function getPokemonFromName(string $name): array
    {
        /** @var Connection $connection */
        $connection = static::getContainer()->get(Connection::class);

        $sql = 'SELECT * FROM pokemon WHERE name = :name';
        $parameters = ['name' => $name];

        /** @var false|string[] $result */
        $result = $connection->executeQuery($sql, $parameters)->fetchAssociative();

        if (false === $result) {
            return [];
        }

        return $result;
    }
}
