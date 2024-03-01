<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Repository\SpecialFormsRepository;

class SpecialFormsService
{
    public function __construct(
        private readonly SpecialFormsRepository $repository,
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
