<?php

declare(strict_types=1);

namespace App\Api\Updater;

use Symfony\Component\Uid\Uuid;

class DexUpdater extends AbstractUpdater
{
    protected string $sheetName = 'Dex';
    protected string $tableName = 'dex';
    protected string $statisticName = 'dex';
    protected string $headerCellsRange = 'A1:M1';

    /** @var string[] */
    protected array $recordsCellsRanges = ['A2:M'];

    protected function getExpectedHeader(): array
    {
        return [
            'Slug',
            'Name',
            'French Name',
            'Order',
            'Selection rule',
            'Is Shiny',
            'Is Display Form',
            'Display template',
            '#Region',
            'French description',
            'Description',
            'Is released',
            'Is Premium',
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
            'selection_rule' => $record['Selection rule'],
            'is_shiny' => $record['Is Shiny'],
            'is_display_form' => $record['Is Display Form'],
            'display_template' => $record['Display template'],
            'region' => $record['#Region'],
            'description' => $record['Description'],
            'french_description' => $record['French description'],
            'is_released' => $record['Is released'],
            'is_premium' => $record['Is Premium'],
        ];

        $tableName = $this->tableName;

        $sql = <<<SQL
            INSERT INTO {$tableName}(
              id,
              slug,
              name,
              french_name,
              order_number,
              selection_rule,
              is_shiny,
              is_display_form,
              display_template,
              region_id,
              description,
              french_description,
              is_released,
              is_premium,
              last_changed_at
            )
            VALUES (
                :id,
                :slug,
                :name,
                :french_name,
                :order_number,
                :selection_rule,
                :is_shiny,
                :is_display_form,
                :display_template,
                (SELECT id FROM region WHERE slug = :region),
                :description,
                :french_description,
                :is_released,
                :is_premium,
                NOW()
            )
            ON CONFLICT (slug)
            DO
            UPDATE
            SET
                name = excluded.name,
                french_name = excluded.french_name,
                order_number = excluded.order_number,
                selection_rule = excluded.selection_rule,
                is_shiny = excluded.is_shiny,
                is_display_form = excluded.is_display_form,
                display_template = excluded.display_template,
                region_id = excluded.region_id,
                description = excluded.description,
                french_description = excluded.french_description,
                is_released = excluded.is_released,
                is_premium = excluded.is_premium,
                deleted_at = NULL
            SQL;

        $this->executeQuery($sql, $sqlParameters);

        $this->statictic->increment();
    }
}
