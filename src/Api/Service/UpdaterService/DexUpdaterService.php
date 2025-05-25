<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\DexUpdater;

class DexUpdaterService extends AbstractUpdaterService
{
    public function __construct(private readonly DexUpdater $dexUpdater) {}

    #[\Override]
    public function execute(): void
    {
        $this->dexUpdater->execute();

        $this->report = new Report([
            $this->dexUpdater->getStatistic(),
        ]);
    }
}
