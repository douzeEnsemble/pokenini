<?php

declare(strict_types=1);

namespace App\Web\Service\CacheInvalidator;

use App\Web\Cache\KeyMaker;
use App\Web\Service\Trait\CacheRegisterTrait;
use Symfony\Contracts\Cache\CacheInterface;

class AbstractCacheInvalidatorService
{
    use CacheRegisterTrait;

    public function __construct(
        protected readonly CacheInterface $cache,
    ) {
    }

    protected function invalidateCacheByType(string $type): void
    {
        foreach ($this->getRegisteredCache($type) as $key) {
            $this->cache->delete($key);
        }

        $this->cache->delete(KeyMaker::getRegisterTypeKey($type));
    }
}
