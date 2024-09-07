<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\CacheInvalidator;

use App\Web\Service\CacheInvalidator\CatchStatesCacheInvalidatorService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * @internal
 *
 * @coversNothing
 */
class CatchStatesCacheInvalidatorServiceTest extends TestCase
{
    public function testInvalidate(): void
    {
        $cache = new ArrayAdapter();
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('catch_states', fn () => 'whatever');

        $service = new CatchStatesCacheInvalidatorService($cache);
        $service->invalidate();

        $values = $cache->getValues();
        $this->assertCount(1, $values);
        $this->assertArrayHasKey('douze', $values);
    }
}
