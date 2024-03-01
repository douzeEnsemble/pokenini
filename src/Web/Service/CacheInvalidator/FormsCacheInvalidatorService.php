<?php

declare(strict_types=1);

namespace App\Web\Service\CacheInvalidator;

use App\Web\Cache\KeyMaker;

class FormsCacheInvalidatorService extends AbstractCacheInvalidatorService
{
    public function invalidate(): void
    {
        $this->cache->delete(KeyMaker::getFormsCategoryKey());
        $this->cache->delete(KeyMaker::getFormsRegionalKey());
        $this->cache->delete(KeyMaker::getFormsSpecialKey());
        $this->cache->delete(KeyMaker::getFormsVariantKey());
    }
}
