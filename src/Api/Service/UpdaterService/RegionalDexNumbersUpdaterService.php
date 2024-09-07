<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\RegionalDexNumbersUpdater;

class RegionalDexNumbersUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly RegionalDexNumbersUpdater $regionalDexNumbersUpdater
    ) {}

    public function execute(): void
    {
        $this->regionalDexNumbersUpdater->execute();

        $this->report = new Report([
            $this->regionalDexNumbersUpdater->getStatistic(),
        ]);
    }
}
