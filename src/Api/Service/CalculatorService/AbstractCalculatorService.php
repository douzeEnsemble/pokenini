<?php

declare(strict_types=1);

namespace App\Api\Service\CalculatorService;

use App\Api\DTO\DataChangeReport\Report;

abstract class AbstractCalculatorService implements CalculatorServiceInterface
{
    protected Report $report;

    public function getReport(): Report
    {
        return $this->report;
    }
}
