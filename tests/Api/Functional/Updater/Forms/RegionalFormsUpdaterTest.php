<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater\Forms;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\Forms\RegionalFormsUpdater;

/**
 * @internal
 *
 * @coversNothing
 */
class RegionalFormsUpdaterTest extends AbstractTestFormsUpdater
{
    protected int $initialTotalCount = 3;
    protected int $finalTotalCount = 4;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'form / Regional form';
    protected string $tableName = 'regional_form';

    protected function getService(): AbstractUpdater
    {
        // @var RegionalFormsUpdater
        return static::getContainer()->get(RegionalFormsUpdater::class);
    }
}
