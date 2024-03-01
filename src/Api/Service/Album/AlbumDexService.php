<?php

declare(strict_types=1);

namespace App\Api\Service\Album;

use App\Api\Repository\DexRepository;

class AlbumDexService
{
    public function __construct(
        private readonly DexRepository $dexRepository,
    ) {
    }

    /**
     * @return string[]|bool[]
     */
    public function get(string $trainerExternalId, string $dexSlug): array
    {
        return $this->dexRepository->getData($trainerExternalId, $dexSlug);
    }
}
