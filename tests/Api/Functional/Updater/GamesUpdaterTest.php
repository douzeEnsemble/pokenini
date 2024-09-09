<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\GamesUpdater;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(GamesUpdater::class)]
#[CoversClass(AbstractUpdater::class)]
class GamesUpdaterTest extends AbstractTestUpdater
{
    protected int $initialTotalCount = 39;
    protected int $finalTotalCount = 39;
    protected int $initialDeletedTotalCount = 1;
    protected int $mustBeDeletedTotalCount = 3;
    protected string $sheetName = 'Game';
    protected string $tableName = 'game';

    protected function getService(): AbstractUpdater
    {
        /** @var GamesUpdater */
        return static::getContainer()->get(GamesUpdater::class);
    }
}
