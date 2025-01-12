<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\DTO\ElectionPokemonsList;
use App\Web\Utils\JsonDecoder;

class GetPokemonsService extends AbstractApiService
{
    public function get(
        string $trainerExternalId,
        string $dexSlug,
        string $electionSlug,
        int $count,
    ): ElectionPokemonsList {
        /** @var string $json */
        $json = $this->requestContent(
            'GET',
            '/pokemons/to_choose',
            [
                'query' => [
                    'trainer_external_id' => $trainerExternalId,
                    'dex_slug' => $dexSlug,
                    'election_slug' => $electionSlug,
                    'count' => $count,
                ],
            ]
        );

        /** @var array{type: string, items: list<array{null|int|string}>} */
        $data = JsonDecoder::decode($json);

        return new ElectionPokemonsList($data);
    }
}
