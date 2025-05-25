<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\CollectionsAvailabilitiesUpdater;

class CollectionsAvailabilitiesUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly CollectionsAvailabilitiesUpdater $updater
    ) {}

    #[\Override]
    public function execute(): void
    {
        $this->updater->execute();

        $this->report = new Report([
            $this->updater->getStatistic(),
        ]);
    }
}
