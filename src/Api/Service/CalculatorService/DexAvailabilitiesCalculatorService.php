<?php

declare(strict_types=1);

namespace App\Api\Service\CalculatorService;

use App\Api\Calculator\DexAvailabilitiesCalculator;
use App\Api\DTO\DataChangeReport\Report;
use Symfony\Contracts\Cache\CacheInterface;

class DexAvailabilitiesCalculatorService extends AbstractCalculatorService
{
    public function __construct(
        private readonly DexAvailabilitiesCalculator $calculator,
        private readonly CacheInterface $cache
    ) {}

    public function execute(): void
    {
        $this->cache->clear();

        $this->calculator->execute();

        $this->report = new Report([
            $this->calculator->getStatistic(),
        ]);
    }
}
