<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\GameBundlesUpdater;

class GameBundlesUpdaterTest extends AbstractTestUpdater
{
    protected int $initialTotalCount = 19;
    protected int $finalTotalCount = 19;
    protected int $mustBeDeletedTotalCount = 1;
    protected string $sheetName = 'Game Bundle';
    protected string $tableName = 'game_bundle';

    protected function getService(): AbstractUpdater
    {
        /** @var GameBundlesUpdater */
        return static::getContainer()->get(GameBundlesUpdater::class);
    }
}
