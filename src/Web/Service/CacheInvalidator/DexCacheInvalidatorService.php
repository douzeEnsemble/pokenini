<?php

declare(strict_types=1);

namespace App\Web\Service\CacheInvalidator;

use App\Web\Cache\KeyMaker;

class DexCacheInvalidatorService extends AbstractCacheInvalidatorService
{
    public function invalidate(): void
    {
        $this->cache->invalidateTags([
            KeyMaker::getDexKey(),
        ]);
    }

    public function invalidateByTrainerId(string $trainerId): void
    {
        $key = KeyMaker::getDexKeyForTrainer($trainerId);

        $this->cache->delete($key);
        $this->cache->invalidateTags([
            KeyMaker::getTrainerIdKey($trainerId),
        ]);
    }
}
