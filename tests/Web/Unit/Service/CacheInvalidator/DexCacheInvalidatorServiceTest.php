<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\CacheInvalidator;

use App\Web\Service\CacheInvalidator\DexCacheInvalidatorService;
use App\Web\Service\Trait\CacheRegisterTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * @internal
 */
#[CoversClass(DexCacheInvalidatorService::class)]
#[CoversClass(CacheRegisterTrait::class)]
class DexCacheInvalidatorServiceTest extends TestCase
{
    public function testInvalidate(): void
    {
        $cache = new ArrayAdapter();
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('dex_123', fn () => 'whatever');
        $cache->get('dex_456', fn () => 'whatever');
        $cache->get('dex_789', fn () => 'whatever');
        $cache->get('register_dex', fn () => ['dex_123', 'dex_456']);

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidate();

        $values = $cache->getValues();
        $this->assertCount(2, $values);
        $this->assertArrayHasKey('douze', $values);
        $this->assertArrayHasKey('dex_789', $values);
    }

    public function testInvalidateMock(): void
    {
        $cache = $this->createMock(ArrayAdapter::class);
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
        $cache = $this->createMock(ArrayAdapter::class);
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
        $cache = new ArrayAdapter();
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('dex_123', fn () => 'whatever');
        $cache->get('dex_456', fn () => 'whatever');
        $cache->get('dex_789', fn () => 'whatever');
        $cache->get('register_dex', fn () => ['dex_123', 'dex_456']);

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidateByTrainerId('unknown');
        $service->invalidateByTrainerId('123');

        $values = $cache->getValues();
        $this->assertCount(4, $values);
        $this->assertArrayHasKey('douze', $values);
        $this->assertArrayHasKey('dex_456', $values);
        $this->assertArrayHasKey('dex_789', $values);

        $this->assertEquals([1 => 'dex_456'], $cache->getItem('register_dex')->get());
    }

    public function testInvalidateByTrainerIdWithHomeDex(): void
    {
        $cache = new ArrayAdapter();
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('dex_123', fn () => 'whatever');
        $cache->get('dex_123#includeprivatedex', fn () => 'whatever');
        $cache->get('dex_456', fn () => 'whatever');
        $cache->get('dex_789', fn () => 'whatever');
        $cache->get('register_dex', fn () => ['dex_123', 'dex_123#includeprivatedex', 'dex_456']);

        $service = new DexCacheInvalidatorService($cache);
        $service->invalidateByTrainerId('unknown');
        $service->invalidateByTrainerId('123');

        $values = $cache->getValues();
        $this->assertCount(4, $values);
        $this->assertArrayNotHasKey('dex_123', $values);
        $this->assertArrayNotHasKey('dex_123includeprivatedex', $values);

        /** @var string[] $register */
        $register = $cache->getItem('register_dex')->get();
        $this->assertCount(1, $register);
    }

    public function testInvalidateByTrainerIdMock(): void
    {
        $cache = $this->createMock(ArrayAdapter::class);
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
