<?php

declare(strict_types=1);

namespace App\Web\Service\CacheInvalidator;

use App\Web\Cache\KeyMaker;

class DexCacheInvalidatorService extends AbstractCacheInvalidatorService
{
    public function invalidate(): void
    {
        $this->invalidateCacheByType(KeyMaker::getDexKey());
    }

    public function invalidateByTrainerId(string $trainerId): void
    {
        $key = KeyMaker::getDexKeyForTrainer($trainerId);

        $this->cache->delete($key);
        $this->unregisterCache(KeyMaker::getDexKey(), $key);

        $dex = $this->getRegisteredCache(KeyMaker::getDexKey());
        foreach ($dex as $dexKey) {
            if (str_contains($dexKey, $key)) {
                $this->cache->delete($dexKey);
                $this->unregisterCache(KeyMaker::getDexKey(), $dexKey);
            }
        }
    }
}
