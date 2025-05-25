<?php

declare(strict_types=1);

namespace App\Api\Updater;

use Symfony\Component\Uid\Uuid;

class CatchStatesUpdater extends AbstractUpdater
{
    protected string $sheetName = 'Catch state';
    protected string $tableName = 'catch_state';
    protected string $statisticName = 'catch_states';
    protected string $headerCellsRange = 'A1:E1';

    /** @var string[] */
    protected array $recordsCellsRanges = ['A2:E'];

    #[\Override]
    protected function getExpectedHeader(): array
    {
        return [
            'Slug',
            'Name',
            'French Name',
            'Order',
            'Color',
        ];
    }

    #[\Override]
    protected function upsertRecord(array $record): void
    {
        $sqlParameters = [
            'id' => (string) Uuid::v4(),
            'slug' => $record['Slug'],
            'name' => $record['Name'],
            'french_name' => $record['French Name'],
            'order_number' => $record['Order'],
            'color' => $record['Color'],
        ];

        $tableName = $this->tableName;

        $sql = <<<SQL
            INSERT INTO {$tableName}(
              id,
              slug,
              name,
              french_name,
              order_number,
              color
            )
            VALUES (
                :id,
                :slug,
                :name,
                :french_name,
                :order_number,
                :color
            )
            ON CONFLICT (slug)
            DO
            UPDATE
            SET
                name = excluded.name,
                french_name = excluded.french_name,
                order_number= excluded.order_number,
                color= excluded.color,
                deleted_at = NULL
            SQL;

        $this->executeQuery($sql, $sqlParameters);

        $this->statictic->increment();
    }
}
