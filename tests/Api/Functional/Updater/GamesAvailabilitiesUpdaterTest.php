<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\GamesAvailabilitiesUpdater;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GamesAvailabilitiesUpdater::class)]
class GamesAvailabilitiesUpdaterTest extends AbstractTestUpdater
{
    use CountGameAvailabilityTrait;

    protected int $initialTotalCount = 28;
    protected int $finalTotalCount = 68970;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'Games Availability';
    protected string $tableName = 'game_availability';

    protected function getService(): AbstractUpdater
    {
        /** @var GamesAvailabilitiesUpdater */
        return static::getContainer()->get(GamesAvailabilitiesUpdater::class);
    }

    /**
     * There is no "deleted_at" field in the table.
     */
    protected function getTableDeletedAtCount(): int
    {
        return 0;
    }
}
