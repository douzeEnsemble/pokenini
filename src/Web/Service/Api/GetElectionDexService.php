<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Cache\KeyMaker;
use App\Web\Service\Trait\CacheRegisterTrait;
use App\Web\Utils\JsonDecoder;

class GetElectionDexService extends AbstractApiService
{
    use CacheRegisterTrait;

    /**
     * @return string[][]
     */
    public function get(): array
    {
        return $this->getDexWithParam('');
    }

    /**
     * @return string[][]
     */
    public function getWithUnreleased(): array
    {
        return $this->getDexWithParam('include_unreleased_dex=1');
    }

    /**
     * @return string[][]
     */
    public function getWithPremium(): array
    {
        return $this->getDexWithParam('include_premium_dex=1');
    }

    /**
     * @return string[][]
     */
    public function getWithUnreleasedAndPremium(): array
    {
        return $this->getDexWithParam('include_unreleased_dex=1&include_premium_dex=1');
    }

    /**
     * @return string[][]
     */
    private function getDexWithParam(string $queryParams = ''): array
    {
        $key = KeyMaker::getElectionDexKey($queryParams);

        /** @var string $json */
        $json = $this->cache->get($key, function () use ($queryParams) {
            return $this->requestContent(
                'GET',
                '/dex/can_hold_election'.($queryParams ? '?'.$queryParams : ''),
            );
        });

        $this->registerCache(KeyMaker::getDexKey(), $key);

        /** @var string[][] */
        return JsonDecoder::decode($json);
    }
}
