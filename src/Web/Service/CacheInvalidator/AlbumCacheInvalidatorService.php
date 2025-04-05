<?php

declare(strict_types=1);

namespace App\Web\Service\CacheInvalidator;

use App\Web\Cache\KeyMaker;

class AlbumCacheInvalidatorService extends AbstractCacheInvalidatorService
{
    public function invalidate(string $dexSlug, string $trainerId): void
    {
        $key = KeyMaker::getPokedexKey($dexSlug, $trainerId);

        $this->cache->delete($key);
        $this->cache->invalidateTags([
            KeyMaker::getTrainerIdKey($trainerId),
        ]);
    }
}
