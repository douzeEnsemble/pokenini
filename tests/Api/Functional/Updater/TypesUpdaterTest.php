<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\TypesUpdater;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(TypesUpdater::class)]
class TypesUpdaterTest extends AbstractTestUpdater
{
    protected int $initialTotalCount = 19;
    protected int $finalTotalCount = 20;
    protected int $initialDeletedTotalCount = 1;
    protected int $mustBeDeletedTotalCount = 2;
    protected string $sheetName = 'Type';
    protected string $tableName = 'type';

    protected function getService(): AbstractUpdater
    {
        /** @var TypesUpdater */
        return static::getContainer()->get(TypesUpdater::class);
    }
}
