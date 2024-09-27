<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\CollectionsUpdater;

class CollectionsUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly CollectionsUpdater $collectionsUpdater,
    ) {}

    public function execute(): void
    {
        $this->collectionsUpdater->execute();

        $this->report = new Report([
            $this->collectionsUpdater->getStatistic(),
        ]);
    }
}
