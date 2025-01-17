<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\DexQueryOptions;
use App\Api\Repository\DexRepository;

class DexCanHoldElectionService
{
    public function __construct(
        private readonly DexRepository $dexRepository,
    ) {}

    /**
     * @return bool[][]|int[][]|string[][]
     */
    public function get(DexQueryOptions $dexQueryOptions): array
    {
        return $this->dexRepository->getCanHoldElection($dexQueryOptions);
    }
}
