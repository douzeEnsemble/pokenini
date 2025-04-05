<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\CacheInvalidator;

use App\Web\Service\CacheInvalidator\DexCacheInvalidatorService;
use App\Web\Service\Trait\CacheRegisterTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * @internal
 */
#[CoversClass(DexCacheInvalidatorService::class)]
#[CoversClass(CacheRegisterTrait::class)]
class DexCacheInvalidatorServiceTest extends TestCase
{
    public function testInvalidate(): void
    {
        $cachePool = new ArrayAdapter();
        $cache = new TagAwareAdapter($cachePool, new ArrayAdapter());
        
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('dex_123', fn () => 'whatever');
        $cache->get('dex_456', fn () => 'whatever');
        $cache->get('dex_789', fn () => 'whatever');
        $cache->get('register_dex', fn () => ['dex_123', 'dex_456']);

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidate();

        $this->assertTrue($cache->hasItem('douze'));
        $this->assertTrue($cache->hasItem('dex_789'));
        $this->assertFalse($cache->hasItem('register_dex'));
        $this->assertFalse($cache->hasItem('dex_123'));
        $this->assertFalse($cache->hasItem('dex_456'));
    }

    public function testInvalidateMock(): void
    {
        $cache = $this->createMock(TagAwareAdapter::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->with('register_dex')
            ->willReturnCallback(function (string $key, callable $callback): mixed {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with('register_dex')
        ;

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidate();
    }

    public function testInvalidateMockNotArray(): void
    {
        $cache = $this->createMock(TagAwareAdapter::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->with('register_dex')
            ->willReturn('not_an_array')
        ;
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with('register_dex')
        ;

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidate();
    }

    public function testInvalidateByTrainerId(): void
    {
        $cachePool = new ArrayAdapter();
        $cache = new TagAwareAdapter($cachePool, new ArrayAdapter());
        
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('dex_123', fn () => 'whatever');
        $cache->get('dex_456', fn () => 'whatever');
        $cache->get('dex_789', fn () => 'whatever');
        $cache->get('register_dex', fn () => ['dex_123', 'dex_456']);

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidateByTrainerId('unknown');
        $service->invalidateByTrainerId('123');

        $this->assertTrue($cache->hasItem('douze'));
        $this->assertTrue($cache->hasItem('dex_456'));
        $this->assertTrue($cache->hasItem('dex_789'));
        $this->assertTrue($cache->hasItem('register_dex'));
        $this->assertFalse($cache->hasItem('dex_123'));

        $this->assertEquals(
            [
                1 => 'dex_456',
            ], 
            $cache->getItem('register_dex')->get()
        );
    }

    public function testInvalidateByTrainerIdWithHomeDex(): void
    {
        $cachePool = new ArrayAdapter();
        $cache = new TagAwareAdapter($cachePool, new ArrayAdapter());
        
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('dex_123', fn () => 'whatever');
        $cache->get('dex_123#includeprivatedex', fn () => 'whatever');
        $cache->get('dex_456', fn () => 'whatever');
        $cache->get('dex_789', fn () => 'whatever');
        $cache->get('register_dex', fn () => ['dex_123', 'dex_123#includeprivatedex', 'dex_456']);

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidateByTrainerId('unknown');
        $service->invalidateByTrainerId('123');

        $this->assertTrue($cache->hasItem('douze'));
        $this->assertTrue($cache->hasItem('dex_456'));
        $this->assertTrue($cache->hasItem('dex_789'));
        $this->assertTrue($cache->hasItem('register_dex'));
        $this->assertFalse($cache->hasItem('dex_123'));
        $this->assertFalse($cache->hasItem('dex_123#includeprivatedex'));

        $this->assertEquals(
            [
                2 => 'dex_456',
            ], 
            $cache->getItem('register_dex')->get()
        );
    }

    public function testInvalidateByTrainerIdMock(): void
    {
        $cache = $this->createMock(TagAwareAdapter::class);
        $cache
            ->expects($this->exactly(3))
            ->method('get')
            ->with('register_dex')
            ->willReturnCallback(function (string $key, callable $callback): mixed {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;
        $cache
            ->expects($this->exactly(2))
            ->method('delete')
        ;

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidateByTrainerId('unknown');
    }
}
