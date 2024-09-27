<?php

declare(strict_types=1);

namespace App\Api\Updater;

use App\Api\Exception\InvalidSheetDataException;
use App\Api\Helper\A1Notation;
use App\Api\Repository\CollectionsRepository;
use App\Api\Service\SpreadsheetService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class CollectionsAvailabilitiesUpdater extends AbstractUpdater
{
    protected const RANGE_SIZE = 100;
    protected const BATCH_SIZE = 20;
    protected string $sheetName = 'Collections Availability';
    protected string $tableName = 'collection_availability';
    protected string $statisticName = 'collections_availabilities';
    protected int $recordsCellsStartRowIndex = 1;
    protected int $recordsCellsStartColumnIndex = 0;

    /** @var string[][] */
    private array $records;

    /**
     * @var null|string[]
     */
    private ?array $collectionsCache = null;

    public function __construct(
        SpreadsheetService $spreadsheetService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        string $spreadsheetId,
        protected readonly CollectionsRepository $collectionsRepository,
    ) {
        parent::__construct(
            $spreadsheetService,
            $entityManager,
            $logger,
            $spreadsheetId
        );
    }

    /**
     * @return string[]
     */
    protected function getHeader(): array
    {
        $collections = $this->getCollections();

        $headerCellsRange = sprintf(
            '%s:%s',
            A1Notation::fromIndex(
                0,
                $this->recordsCellsStartColumnIndex,
            ),
            A1Notation::fromIndex(
                0,
                count($collections)
            ),
        );

        $values = $this->getSheetValues("'{$this->sheetName}'!{$headerCellsRange}");

        if (!$values) {
            throw new InvalidSheetDataException('Spreadsheet is empty');
        }

        return $values[0];
    }

    /**
     * @return string[]
     */
    protected function getRecordsCellsRanges(): array
    {
        $rowCount = $this->spreadsheetService->getSheetRowCount($this->spreadsheetId, $this->sheetName);
        $columnCount = $this->spreadsheetService->getSheetColumnCount($this->spreadsheetId, $this->sheetName);

        $nbBatch = ($rowCount / self::RANGE_SIZE);

        $ranges = [];
        for ($i = 0; $i < $nbBatch; ++$i) {
            $startRow = $this->recordsCellsStartRowIndex + (self::RANGE_SIZE * $i);
            $endRow = min($startRow + self::RANGE_SIZE - 1, $rowCount - 1);

            $ranges[] = sprintf(
                '%s:%s',
                A1Notation::fromIndex($startRow, $this->recordsCellsStartColumnIndex),
                A1Notation::fromIndex($endRow, $this->recordsCellsStartColumnIndex + $columnCount - 1),
            );
        }

        return $ranges;
    }

    protected function getExpectedHeader(): array
    {
        $collections = $this->getCollections();

        return array_merge(
            [
                '#Pokemon',
            ],
            $collections,
        );
    }

    protected function getRecords(array $header, string $range): array
    {
        $values = $this->getRecordsData($range);
        $newValues = $this->transformRecords($values, $header);
        unset($values);

        $this->getFromRecords($newValues);

        return $this->records;
    }

    /**
     * @param string[] $header
     */
    protected function handleCellRange(array $header, string $cellRange): void
    {
        $records = $this->getRecords($header, $cellRange);

        $availabilitiesChunks = array_chunk($records, self::BATCH_SIZE);
        unset($records);
        foreach ($availabilitiesChunks as $chunk) {
            $this->upsertRecords($chunk);
        }
    }

    /**
     * @param bool[][]|string[][] $records
     */
    protected function upsertRecords(array $records): void
    {
        $sqlValues = [];
        $sqlParameters = [];
        $index = 0;
        foreach ($records as $record) {
            $sqlValues[] = ":id{$index}"
                .", :pokemonSlug{$index}"
                .", (SELECT id FROM collection WHERE slug = :collectionSlug{$index})"
                .", :availability{$index}";

            $sqlParameters["id{$index}"] = Uuid::v4();
            $sqlParameters["pokemonSlug{$index}"] = $record['pokemonSlug'];
            $sqlParameters["collectionSlug{$index}"] = $record['collectionSlug'];
            $sqlParameters["availability{$index}"] = $record['availability'];

            ++$index;
        }

        $sqlValuesStr = implode('), (', $sqlValues);

        $sql = <<<SQL
                    INSERT INTO {$this->tableName} (
                        id,
                        pokemon_slug,
                        collection_id,
                        availability
                    )
                    VALUES ({$sqlValuesStr})
            SQL;

        $this->executeQuery($sql, $sqlParameters);

        $this->statictic->incrementBy($index);
    }

    // @codeCoverageIgnoreStart
    protected function upsertRecord(array $record): void
    {
        throw new \RuntimeException(
            "Don't use this method."
        );
    }
    // @codeCoverageIgnoreEnd

    protected function removeExistingRecords(): void
    {
        $tableName = $this->tableName;

        $sql = <<<SQL
                DELETE FROM {$tableName}
            SQL;

        $this->entityManager->getConnection()->executeQuery($sql);
    }

    /**
     * @param string[][] $records
     */
    private function getFromRecords(array $records): void
    {
        $this->records = [];
        foreach ($records as $record) {
            $this->transformRecord($record);
        }
    }

    /**
     * @param string[] $record
     */
    private function transformRecord(
        array $record
    ): void {
        $slug = $record['#Pokemon'];
        unset($record['#Pokemon']);

        foreach ($record as $collectionSlug => $availability) {
            $this->records[] = [
                'pokemonSlug' => $slug,
                'collectionSlug' => $collectionSlug,
                'availability' => $availability,
            ];
        }
    }

    /**
     * @return string[]
     */
    private function getCollections(): array
    {
        if (null != $this->collectionsCache) {
            return $this->collectionsCache;
        }

        $this->collectionsCache = $this->collectionsRepository->getAllSlugs();

        return $this->collectionsCache;
    }
}
