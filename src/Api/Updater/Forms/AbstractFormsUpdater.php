<?php

declare(strict_types=1);

namespace App\Api\Updater\Forms;

use App\Api\Updater\AbstractUpdater;
use Symfony\Component\Uid\Uuid;

abstract class AbstractFormsUpdater extends AbstractUpdater
{
    protected string $headerCellsRange = 'A1:D1';
    protected array $recordsCellsRanges = ['A2:D'];

    protected function getExpectedHeader(): array
    {
        return [
            'Name',
            'French Name',
            'Slug',
            'Order',
        ];
    }

    protected function upsertRecord(array $record): void
    {
        $sqlParameters = [
            'id' => (string) Uuid::v4(),
            'slug' => $record['Slug'],
            'name' => $record['Name'],
            'french_name' => $record['French Name'],
            'order_number' => $record['Order'],
        ];

        $tableName = $this->tableName;

        $sql = <<<SQL
            INSERT INTO {$tableName}(
              id,
              slug,
              name,
              french_name,
              order_number
            )
            VALUES (
                :id,
                :slug,
                :name,
                :french_name,
                :order_number
            )
            ON CONFLICT (slug)
            DO
            UPDATE
            SET
                name = excluded.name,
                french_name = excluded.french_name,
                order_number= excluded.order_number,
                deleted_at = NULL
            SQL;

        $this->executeQuery($sql, $sqlParameters);

        $this->statictic->increment();
    }
}
