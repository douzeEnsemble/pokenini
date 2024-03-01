<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\GamesAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GamesAvailabilitiesRepository;
use Symfony\Contracts\Cache\CacheInterface;

class GamesAvailabilitiesService
{
    private const CACHE_PREFIX = 'ga-';

    public function __construct(
        private readonly GamesAvailabilitiesRepository $repository,
        private readonly CacheInterface $cache
    ) {
    }

    public function getFromPokemon(Pokemon $pokemon): GamesAvailabilities
    {
        /** @var GamesAvailabilities */
        return $this->cache->get(
            $this->getCacheKey($pokemon),
            function () use ($pokemon) {
                return $this->repository->getFromPokemon($pokemon);
            }
        );
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
