<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Entity\Dex;
use App\Api\Entity\DexAvailability;
use App\Api\Repository\DexAvailabilitiesRepository;

class DexAvailabilitiesService
{
    public function __construct(
        private readonly DexAvailabilitiesRepository $repository,
    ) {}

    /**
     * @return DexAvailability[]
     */
    public function getByDex(Dex $dex): array
    {
        return $this->repository->findBy(['dex' => $dex]);
    }
}
