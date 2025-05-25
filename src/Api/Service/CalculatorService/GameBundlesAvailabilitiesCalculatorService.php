<?php

declare(strict_types=1);

namespace App\Api\Service\CalculatorService;

use App\Api\Calculator\GameBundlesAvailabilitiesCalculator;
use App\Api\DTO\DataChangeReport\Report;

class GameBundlesAvailabilitiesCalculatorService extends AbstractCalculatorService
{
    public function __construct(
        private readonly GameBundlesAvailabilitiesCalculator $calculator
    ) {}

    #[\Override]
    public function execute(): void
    {
        $this->calculator->execute();

        $this->report = new Report([
            $this->calculator->getStatistic(),
        ]);
    }
}
