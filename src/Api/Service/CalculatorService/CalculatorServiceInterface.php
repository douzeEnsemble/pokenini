<?php

declare(strict_types=1);

namespace App\Api\Service\CalculatorService;

use App\Api\DTO\DataChangeReport\Report;

interface CalculatorServiceInterface
{
    public function execute(): void;

    public function getReport(): Report;
}
