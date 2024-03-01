<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\GamesUpdater;

class GamesUpdaterTest extends AbstractTestUpdater
{
    protected int $initialTotalCount = 38;
    protected int $finalTotalCount = 38;
    protected int $mustBeDeletedTotalCount = 2;
    protected string $sheetName = 'Game';
    protected string $tableName = 'game';

    protected function getService(): AbstractUpdater
    {
        /** @var GamesUpdater */
        return static::getContainer()->get(GamesUpdater::class);
    }
}
