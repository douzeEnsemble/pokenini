<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\CatchStatesUpdater;
use App\Api\Updater\RegionsUpdater;
use App\Api\Updater\TypesUpdater;

class LabelsUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly CatchStatesUpdater $catchStatesUpdater,
        private readonly FormsUpdaterService $formsUpdaterService,
        private readonly RegionsUpdater $regionsUpdater,
        private readonly TypesUpdater $typesUpdater,
    ) {}

    public function execute(): void
    {
        $this->catchStatesUpdater->execute();
        $this->formsUpdaterService->execute();
        $this->regionsUpdater->execute();
        $this->typesUpdater->execute();

        $this->report = new Report([
            $this->catchStatesUpdater->getStatistic(),
            $this->regionsUpdater->getStatistic(),
            $this->typesUpdater->getStatistic(),
        ]);

        $this->report->merge($this->formsUpdaterService->getReport());
    }
}
