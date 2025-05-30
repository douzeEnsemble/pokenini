<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\GamesShiniesAvailabilitiesUpdater;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameShinyAvailabilityTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GamesShiniesAvailabilitiesUpdater::class)]
#[CoversClass(AbstractUpdater::class)]
class GamesShiniesAvailabilitiesUpdaterTest extends AbstractTestUpdater
{
    use CountGameShinyAvailabilityTrait;

    protected int $initialTotalCount = 22;
    protected int $finalTotalCount = 4598;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'Games Shinies Availability';
    protected string $tableName = 'game_shiny_availability';

    #[\Override]
    protected function getService(): AbstractUpdater
    {
        /** @var GamesShiniesAvailabilitiesUpdater */
        return static::getContainer()->get(GamesShiniesAvailabilitiesUpdater::class);
    }

    /**
     * There is no "deleted_at" field in the table.
     */
    #[\Override]
    protected function getTableDeletedAtCount(): int
    {
        return 0;
    }
}
