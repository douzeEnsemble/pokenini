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
     * @return string[][]|string[][][]|int[][]
     */
    public function get(string $trainerExternalId, string $dexSlug, AlbumFilters $albumFilters): array
    {
        $pokemons = $this->pokedexRepository->getList(
            $trainerExternalId,
            $dexSlug,
            $albumFilters,
        );

        $list = $this->explodesFlatList($pokemons);

        return $list;
    }

    /**
     * @param string[][]|int[][] &$pokemons
     *
     * @return string[][]|string[][][]|int[][]
     */
    private function explodesFlatList(array $pokemons): array
    {
        $list = [];

        foreach ($pokemons as $pokemon) {
            /** @var string */
            $gameBundleSlugs = $pokemon['game_bundle_slugs'] ?? '';
            /** @var string */
            $gameBundleSShinylugs = $pokemon['game_bundle_shiny_slugs'] ?? '';

            $pokemon['game_bundles'] = array_filter(explode(',', $gameBundleSlugs));
            $pokemon['game_bundles_shiny'] = array_filter(explode(',', $gameBundleSShinylugs));

            unset($pokemon['game_bundle_slugs']);
            unset($pokemon['game_bundle_shiny_slugs']);

            $list[] = $pokemon;
        }

        return $list;
    }
}
