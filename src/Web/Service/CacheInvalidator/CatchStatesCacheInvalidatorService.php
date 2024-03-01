<?php

declare(strict_types=1);

namespace App\Web\Service\CacheInvalidator;

use App\Web\Cache\KeyMaker;

class CatchStatesCacheInvalidatorService extends AbstractCacheInvalidatorService
{
    public function invalidate(): void
    {
        $this->cache->delete(KeyMaker::getCatchStatesKey());
    }
}
