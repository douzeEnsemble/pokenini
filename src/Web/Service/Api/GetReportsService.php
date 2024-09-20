<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Cache\KeyMaker;
use App\Web\Utils\JsonDecoder;

class GetReportsService extends AbstractApiService
{
    /**
     * @return string[][]
     */
    public function get(): array
    {
        $key = KeyMaker::getReportsKey();

        /** @var string $json */
        $json = $this->cache->get($key, function () {
            return $this->requestContent(
                'GET',
                '/reports',
            );
        });

        /** @var string[][] */
        return JsonDecoder::decode($json);
    }
}
