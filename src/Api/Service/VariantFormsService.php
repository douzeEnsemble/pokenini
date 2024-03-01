<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Repository\VariantFormsRepository;

class VariantFormsService
{
    public function __construct(
        private readonly VariantFormsRepository $repository,
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
