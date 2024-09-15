<?php

declare(strict_types=1);

namespace App\Api\Calculator;

use App\Api\DTO\DataChangeReport\Statistic;

abstract class AbstractCalculator implements CalculatorInterface
{
    protected string $statisticName;
    protected Statistic $statictic;

    public function init(): void
    {
        $this->statictic = new Statistic($this->statisticName);
    }

    public function getStatistic(): Statistic
    {
        return $this->statictic;
    }
}
