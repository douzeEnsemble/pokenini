<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Utils\JsonDecoder;

class GetPokemonsService extends AbstractApiService
{
    /**
     * @return string[][]
     */
    public function get(int $count): array
    {
        /** @var string $json */
        $json = $this->requestContent(
            'GET',
            '/pokemons/list/'.$count,
        );

        /** @var string[][] */
        return JsonDecoder::decode($json);
    }
}
