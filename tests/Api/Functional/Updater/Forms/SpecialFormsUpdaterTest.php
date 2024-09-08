<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater\Forms;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\Forms\SpecialFormsUpdater;

/**
 * @internal
 *
 * @coversNothing
 */
class SpecialFormsUpdaterTest extends AbstractTestFormsUpdater
{
    protected int $initialTotalCount = 4;
    protected int $finalTotalCount = 5;
    protected int $mustBeDeletedTotalCount = 0;
    protected string $sheetName = 'form / Special form';
    protected string $tableName = 'special_form';

    protected function getService(): AbstractUpdater
    {
        /** @var SpecialFormsUpdater */
        return static::getContainer()->get(SpecialFormsUpdater::class);
    }
}
