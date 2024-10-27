<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\GameBundlesShiniesAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GameBundlesShiniesAvailabilitiesRepository;
use Symfony\Contracts\Cache\CacheInterface;

class GameBundlesShiniesAvailabilitiesService
{
    private const string CACHE_PREFIX = 'gbsa-';

    public function __construct(
        private readonly GameBundlesShiniesAvailabilitiesRepository $repository,
        private readonly CacheInterface $cache
    ) {}

    public function getFromPokemon(Pokemon $pokemon): GameBundlesShiniesAvailabilities
    {
        $key = $this->getCacheKey($pokemon);

        /** @var GameBundlesShiniesAvailabilities */
        return $this->cache->get($key, function () use ($pokemon) {
            return $this->repository->getFromPokemon($pokemon);
        });
    }

    public function cleanCacheFromPokemon(Pokemon $pokemon): void
    {
        $this->cache->delete($this->getCacheKey($pokemon));
    }

    private function getCacheKey(Pokemon $pokemon): string
    {
        return self::CACHE_PREFIX.$pokemon->slug;
    }
}
