<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\RegionsUpdater;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(RegionsUpdater::class)]
#[CoversClass(AbstractUpdater::class)]
class RegionsUpdaterTest extends AbstractTestUpdater
{
    protected int $initialTotalCount = 10;
    protected int $finalTotalCount = 10;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'Region';
    protected string $tableName = 'region';

    #[\Override]
    protected function getService(): AbstractUpdater
    {
        /** @var RegionsUpdater */
        return static::getContainer()->get(RegionsUpdater::class);
    }
}
