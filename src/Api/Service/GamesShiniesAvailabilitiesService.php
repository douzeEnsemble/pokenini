<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\GamesShiniesAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GamesShiniesAvailabilitiesRepository;
use Symfony\Contracts\Cache\CacheInterface;

class GamesShiniesAvailabilitiesService
{
    private const CACHE_PREFIX = 'gsa-';

    public function __construct(
        private readonly GamesShiniesAvailabilitiesRepository $repository,
        private readonly CacheInterface $cache
    ) {}

    public function getFromPokemon(Pokemon $pokemon): GamesShiniesAvailabilities
    {
        $key = $this->getCacheKey($pokemon);

        /** @var GamesShiniesAvailabilities */
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
