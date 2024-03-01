<?php

declare(strict_types=1);

namespace App\Api\Service\Album;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\Repository\PokedexRepository;

class AlbumPokemonService
{
    public function __construct(
        private readonly PokedexRepository $pokedexRepository,
    ) {
    }

    /**
     * @return string[][]|int[][]
     */
    public function get(string $trainerExternalId, string $dexSlug, AlbumFilters $albumFilters): array
    {
        /** @var string[][]|int[][] */
        return iterator_to_array(
            $this->pokedexRepository->getListQuery(
                $trainerExternalId,
                $dexSlug,
                $albumFilters,
            )
        );
    }
}
