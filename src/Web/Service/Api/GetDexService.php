<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Cache\KeyMaker;
use App\Web\Service\Trait\CacheRegisterTrait;
use App\Web\Utils\JsonDecoder;
use Symfony\Contracts\Cache\ItemInterface;

class GetDexService extends AbstractApiService
{
    use CacheRegisterTrait;

    /**
     * @return string[][]
     */
    public function get(string $trainerId): array
    {
        return $this->getDexWithParam($trainerId, '');
    }

    /**
     * @return string[][]
     */
    public function getWithUnreleased(string $trainerId): array
    {
        return $this->getDexWithParam($trainerId, 'include_unreleased_dex=1');
    }

    /**
     * @return string[][]
     */
    public function getWithPremium(string $trainerId): array
    {
        return $this->getDexWithParam($trainerId, 'include_premium_dex=1');
    }

    /**
     * @return string[][]
     */
    public function getWithUnreleasedAndPremium(string $trainerId): array
    {
        return $this->getDexWithParam($trainerId, 'include_unreleased_dex=1&include_premium_dex=1');
    }

    /**
     * @return string[][]
     */
    private function getDexWithParam(string $trainerId, string $queryParams = ''): array
    {
        $key = KeyMaker::getDexKeyForTrainer($trainerId);

        /** @var string $json */
        $json = $this->cache->get($key, function (ItemInterface $item) use ($trainerId, $queryParams) {
            return $this->requestContent(
                'GET',
                "/dex/{$trainerId}/list".($queryParams ? '?'.$queryParams : ''),
            );
        });

        $this->registerCache(KeyMaker::getDexKey(), $key);

        /** @var string[][] */
        return JsonDecoder::decode($json);
    }
}
