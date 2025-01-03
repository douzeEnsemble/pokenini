<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Utils\JsonDecoder;

class GetPokemonsService extends AbstractApiService
{
    /**
     * @return string[][]
     */
    public function get(string $dexSlug, int $count): array
    {
        /** @var string $json */
        $json = $this->requestContent(
            'GET',
            "/pokemons/list/{$dexSlug}/{$count}",
        );

        /** @var string[][] */
        return JsonDecoder::decode($json);
    }
}
