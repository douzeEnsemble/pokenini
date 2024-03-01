<?php

declare(strict_types=1);

namespace App\Api\Updater;

use Symfony\Component\Uid\Uuid;

class GameGenerationsUpdater extends AbstractUpdater
{
    protected string $sheetName = 'Game Generation';
    protected string $tableName = 'game_generation';
    protected string $statisticName = 'game_generations';
    protected string $headerCellsRange = 'A1:B1';
    /** @var string[] */
    protected array $recordsCellsRanges = ['A2:B'];

    protected function getExpectedHeader(): array
    {
        return [
            'Slug',
            'Name',
        ];
    }

    protected function upsertRecord(array $record): void
    {
        $sqlParameters = [
            'id' => (string) Uuid::v4(),
            'slug' => $record['Slug'],
            'name' => $record['Name'],
        ];

        $tableName = $this->tableName;

        $sql = <<<SQL
        INSERT INTO $tableName(
          id,
          slug,
          name
        )
        VALUES (
            :id,
            :slug,
            :name
        )
        ON CONFLICT (slug)
        DO
        UPDATE
        SET
            name = excluded.name,
            deleted_at = NULL
        SQL;

        $this->executeQuery($sql, $sqlParameters);

        $this->statictic->increment();
    }
}
