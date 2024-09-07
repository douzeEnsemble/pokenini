<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\GamesShiniesAvailabilitiesUpdater;

class GamesShiniesAvailabilitiesUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly GamesShiniesAvailabilitiesUpdater $updater
    ) {}

    public function execute(): void
    {
        $this->updater->execute();

        $this->report = new Report([
            $this->updater->getStatistic(),
        ]);
    }
}
