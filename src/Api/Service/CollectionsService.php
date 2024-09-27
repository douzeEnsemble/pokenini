<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Repository\CollectionsRepository;

class CollectionsService
{
    public function __construct(
        private readonly CollectionsRepository $repository,
    ) {}

    /**
     * @return string[][]
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }
}
