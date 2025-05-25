<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\PokemonsUpdater;

class PokemonsUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly PokemonsUpdater $pokemonsUpdater
    ) {}

    #[\Override]
    public function execute(): void
    {
        $this->pokemonsUpdater->execute();

        $this->report = new Report([
            $this->pokemonsUpdater->getStatistic(),
        ]);
    }
}
