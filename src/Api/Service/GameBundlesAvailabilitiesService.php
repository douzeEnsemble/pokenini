<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\GameBundlesAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GameBundlesAvailabilitiesRepository;
use Symfony\Contracts\Cache\CacheInterface;

class GameBundlesAvailabilitiesService
{
    private const CACHE_PREFIX = 'gba-';

    public function __construct(
        private readonly GameBundlesAvailabilitiesRepository $repository,
        private readonly CacheInterface $cache
    ) {
    }

    public function getFromPokemon(Pokemon $pokemon): GameBundlesAvailabilities
    {
        $key = self::CACHE_PREFIX . $pokemon->slug;

        /** @var GameBundlesAvailabilities */
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
        return self::CACHE_PREFIX . $pokemon->slug;
    }
}
