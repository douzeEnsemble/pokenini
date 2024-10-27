<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\CacheInvalidator;

use App\Web\Service\CacheInvalidator\AlbumCacheInvalidatorService;
use App\Web\Service\Trait\CacheRegisterTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * @internal
 */
#[CoversClass(AlbumCacheInvalidatorService::class)]
#[CoversClass(CacheRegisterTrait::class)]
class AlbumCacheInvalidatorServiceTest extends TestCase
{
    public function testInvalidate(): void
    {
        $cache = new ArrayAdapter();
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('album_home_123', fn () => 'whatever');
        $cache->get('album_home_456', fn () => 'whatever');
        $cache->get('register_album', fn () => ['album_home_123', 'album_home_456']);

        $service = new AlbumCacheInvalidatorService($cache);
        $service->invalidate('unknown', '123');
        $service->invalidate('home', '123');

        $values = $cache->getValues();
        $this->assertCount(3, $values);
        $this->assertArrayHasKey('douze', $values);
        $this->assertArrayHasKey('album_home_456', $values);
        $this->assertArrayHasKey('register_album', $values);

        /** @var string[] $register */
        $register = $cache->getItem('register_album')->get();
        $this->assertCount(1, $register);
    }

    public function testInvalidateMock(): void
    {
        $cache = $this->createMock(ArrayAdapter::class);
        $cache
            ->expects($this->exactly(2))
            ->method('get')
            ->with('register_album')
            ->willReturnCallback(function (string $key, callable $callback): array {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;
        $cache
            ->expects($this->exactly(2))
            ->method('delete')
        ;

        $service = new AlbumCacheInvalidatorService($cache);
        $service->invalidate('unknown', '123');
    }
}
