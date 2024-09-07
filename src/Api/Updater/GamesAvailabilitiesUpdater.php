<?php

declare(strict_types=1);

namespace App\Api\Updater;

use App\Api\Exception\InvalidSheetDataException;
use App\Api\Helper\A1Notation;
use App\Api\Repository\GamesRepository;
use App\Api\Service\SpreadsheetService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Psr\Log\LoggerInterface;
use Symfony\Component\Uid\Uuid;

class GamesAvailabilitiesUpdater extends AbstractUpdater
{
    protected const RANGE_SIZE = 100;
    protected const BATCH_SIZE = 20;
    protected string $sheetName = 'Games Availability';
    protected string $tableName = 'game_availability';
    protected string $statisticName = 'games_availabilities';
    protected int $recordsCellsStartRowIndex = 2;
    protected int $recordsCellsStartColumnIndex = 0;

    /** @var string[][] */
    private array $records;

    /**
     * @var null|string[]
     */
    private ?array $gamesCache = null;

    public function __construct(
        SpreadsheetService $spreadsheetService,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        string $spreadsheetId,
        protected readonly GamesRepository $gamesRepository
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
        $games = $this->getGames();

        $headerCellsRange = sprintf(
            '%s:%s',
            A1Notation::fromIndex(
                1,
                $this->recordsCellsStartColumnIndex
            ),
            A1Notation::fromIndex(
                1,
                count($games)
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
        $games = $this->getGames();

        return array_merge(
            [
                '#Pokemon',
            ],
            $games
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
                .", (SELECT id FROM game WHERE slug = :game{$index})"
                .", :availability{$index}";

            $sqlParameters["id{$index}"] = Uuid::v4();
            $sqlParameters["pokemonSlug{$index}"] = $record['pokemonSlug'];
            $sqlParameters["game{$index}"] = $record['game'];
            $sqlParameters["availability{$index}"] = $record['availability'];

            ++$index;
        }

        $sqlValuesStr = implode('), (', $sqlValues);

        $sql = <<<SQL
                    INSERT INTO {$this->tableName} (
                        id,
                        pokemon_slug,
                        game_id,
                        availability
                    )
                    VALUES ({$sqlValuesStr})
            SQL;

        $this->executeQuery($sql, $sqlParameters);

        $this->statictic->incrementBy($index);
    }

    #[CodeCoverageIgnore]
    protected function upsertRecord(array $record): void
    {
        throw new \RuntimeException(
            "Don't use this method."
        );
    }

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

        foreach ($record as $game => $availability) {
            $this->records[] = [
                'pokemonSlug' => $slug,
                'game' => $game,
                'availability' => $availability,
            ];
        }
    }

    /**
     * @return string[]
     */
    private function getGames(): array
    {
        if (null != $this->gamesCache) {
            return $this->gamesCache;
        }

        $this->gamesCache = $this->gamesRepository->getAllSlugs();

        return $this->gamesCache;
    }
}
