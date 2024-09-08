<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Cache\KeyMaker;
use App\Web\Utils\JsonDecoder;

class GetPokedexService extends AbstractApiService
{
    /**
     * @param string[]|string[][] $filters
     *
     * @return string[][][]
     */
    public function get(
        string $dexSlug,
        string $trainerId,
        array $filters = [],
    ): array {
        $key = KeyMaker::getPokedexKey($dexSlug, $trainerId, $filters);

        /** @var string $json */
        $json = $this->cache->get($key, function () use ($dexSlug, $trainerId, $filters) {
            $url = "/album/{$trainerId}/{$dexSlug}";

            return $this->requestContent(
                'GET',
                $url,
                [
                    'query' => $filters,
                ],
            );
        });

        $this->registerCache(KeyMaker::getAlbumKey(), $key);

        /** @var string[][][] */
        return JsonDecoder::decode($json);
    }
}
