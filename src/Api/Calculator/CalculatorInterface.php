<?php

declare(strict_types=1);

namespace App\Api\Calculator;

use App\Api\DTO\DataChangeReport\Statistic;

interface CalculatorInterface
{
    public function execute(): void;

    public function getStatistic(): Statistic;
}
