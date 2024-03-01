<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;

abstract class AbstractUpdaterService implements UpdaterServiceInterface
{
    protected Report $report;

    public function getReport(): Report
    {
        return $this->report;
    }
}
