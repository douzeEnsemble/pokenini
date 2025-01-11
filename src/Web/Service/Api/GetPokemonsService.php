<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Utils\JsonDecoder;

class GetPokemonsService extends AbstractApiService
{
    /**
     * @return string[][]
     */
    public function get(
        string $trainerExternalId,
        string $dexSlug,
        string $electionSlug,
        int $count,
    ): array {
        /** @var string $json */
        $json = $this->requestContent(
            'GET',
            '/pokemons/list',
            [
                'query' => [
                    'trainer_external_id' => $trainerExternalId,
                    'dex_slug' => $dexSlug,
                    'election_slug' => $electionSlug,
                    'count' => $count,
                ],
            ]
        );

        /** @var string[][] */
        return JsonDecoder::decode($json);
    }
}
