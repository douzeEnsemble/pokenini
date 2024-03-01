<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Repository\CategoryFormsRepository;

class CategoryFormsService
{
    public function __construct(
        private readonly CategoryFormsRepository $repository,
    ) {
    }

    /**
     * @return string[][]
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }
}
