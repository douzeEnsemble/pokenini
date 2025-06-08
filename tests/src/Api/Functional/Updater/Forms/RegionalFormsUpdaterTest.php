<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater\Forms;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\Forms\RegionalFormsUpdater;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(RegionalFormsUpdater::class)]
class RegionalFormsUpdaterTest extends AbstractTestFormsUpdater
{
    protected int $initialTotalCount = 3;
    protected int $finalTotalCount = 4;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'form / Regional form';
    protected string $tableName = 'regional_form';

    #[\Override]
    protected function getService(): AbstractUpdater
    {
        /** @var RegionalFormsUpdater */
        return static::getContainer()->get(RegionalFormsUpdater::class);
    }
}
