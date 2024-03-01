<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Tests\Api\Common\Traits\CounterTrait\CountGameShinyAvailabilityTrait;
use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\GamesShiniesAvailabilitiesUpdater;

class GamesShiniesAvailabilitiesUpdaterTest extends AbstractTestUpdater
{
    use CountGameShinyAvailabilityTrait;

    protected int $initialTotalCount = 22;
    protected int $finalTotalCount = 4598;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'Games Shinies Availability';
    protected string $tableName = 'game_shiny_availability';

    protected function getService(): AbstractUpdater
    {
        /** @var GamesShiniesAvailabilitiesUpdater */
        return static::getContainer()->get(GamesShiniesAvailabilitiesUpdater::class);
    }

    /**
     * There is no "deleted_at" field in the table
     */
    protected function getTableDeletedAtCount(): int
    {
        return 0;
    }
}
