<?php

declare(strict_types=1);

namespace App\Api\Service\CalculatorService;

use App\Api\Calculator\GameBundlesShiniesAvailabilitiesCalculator;
use App\Api\DTO\DataChangeReport\Report;

class GameBundlesShiniesAvailabilitiesCalculatorService extends AbstractCalculatorService
{
    public function __construct(
        private readonly GameBundlesShiniesAvailabilitiesCalculator $calculator
    ) {
    }

    public function execute(): void
    {
        $this->calculator->execute();

        $this->report = new Report([
            $this->calculator->getStatistic(),
        ]);
    }
}
