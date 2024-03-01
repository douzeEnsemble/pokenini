<?php

declare(strict_types=1);

namespace App\Api\Updater;

use App\Api\DTO\DataChangeReport\Statistic;

interface UpdaterInterface
{
    public function execute(?string $sheetName = null): void;

    public function getStatistic(): Statistic;
}
