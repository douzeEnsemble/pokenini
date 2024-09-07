<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Repository\RegionalFormsRepository;

class RegionalFormsService
{
    public function __construct(
        private readonly RegionalFormsRepository $repository,
    ) {}

    /**
     * @return string[][]
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }
}
