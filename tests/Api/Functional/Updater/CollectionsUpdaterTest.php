<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\CollectionsUpdater;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(CollectionsUpdater::class)]
#[CoversClass(AbstractUpdater::class)]
class CollectionsUpdaterTest extends AbstractTestUpdater
{
    protected int $initialTotalCount = 8;
    protected int $finalTotalCount = 9;
    protected int $initialDeletedTotalCount = 0;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'Collection';
    protected string $tableName = 'collection';

    protected function getService(): AbstractUpdater
    {
        /** @var CollectionsUpdater */
        return static::getContainer()->get(CollectionsUpdater::class);
    }
}
