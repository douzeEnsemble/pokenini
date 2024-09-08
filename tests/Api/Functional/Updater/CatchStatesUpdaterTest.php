<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\CatchStatesUpdater;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(CatchStatesUpdater::class)]
class CatchStatesUpdaterTest extends AbstractTestUpdater
{
    protected int $initialTotalCount = 5;
    protected int $finalTotalCount = 9;
    protected int $initialDeletedTotalCount = 1;
    protected int $mustBeDeletedTotalCount = 3;
    protected string $sheetName = 'Catch state';
    protected string $tableName = 'catch_state';

    protected function getService(): AbstractUpdater
    {
        /** @var CatchStatesUpdater */
        return static::getContainer()->get(CatchStatesUpdater::class);
    }
}
