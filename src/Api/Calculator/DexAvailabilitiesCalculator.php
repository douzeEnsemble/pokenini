<?php

declare(strict_types=1);

namespace App\Api\Calculator;

use App\Api\Entity\Dex;
use App\Api\Repository\DexAvailabilitiesRepository;
use App\Api\Repository\DexRepository;

class DexAvailabilitiesCalculator extends AbstractCalculator
{
    protected string $statisticName = 'dex_availabilities';

    public function __construct(
        private readonly DexAvailabilitiesRepository $dexAvailabilitiesRepo,
        private readonly DexRepository $dexRepository,
        private readonly DexAvailabilityCalculator $dexAvailabilityCalculator,
    ) {}

    #[\Override]
    public function execute(): void
    {
        $this->init();

        $this->dexAvailabilitiesRepo->removeAll();

        $dexQuery = $this->dexRepository->getQueryAll();

        /** @var Dex $dex */
        foreach ($dexQuery->toIterable() as $dex) {
            $count = $this->dexAvailabilityCalculator->calculate($dex);

            $this->statictic->incrementBy($count);
        }
    }
}
