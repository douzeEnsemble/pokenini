<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Repository\TypesRepository;

class TypesService
{
    public function __construct(
        private readonly TypesRepository $repository,
    ) {}

    /**
     * @return string[][]
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }
}
