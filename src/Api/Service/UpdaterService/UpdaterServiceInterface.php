<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;

interface UpdaterServiceInterface
{
    public function execute(): void;

    public function getReport(): Report;
}
