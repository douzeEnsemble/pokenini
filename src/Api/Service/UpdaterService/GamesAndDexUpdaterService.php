<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;

class GamesAndDexUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly GamesUpdaterService $gamesUpdaterService,
        private readonly DexUpdaterService $dexUpdaterService,
    ) {
    }

    public function execute(): void
    {
        $this->gamesUpdaterService->execute();
        $this->dexUpdaterService->execute();

        $this->report = new Report([]);
        $this->report->merge($this->gamesUpdaterService->getReport());
        $this->report->merge($this->dexUpdaterService->getReport());
    }
}
