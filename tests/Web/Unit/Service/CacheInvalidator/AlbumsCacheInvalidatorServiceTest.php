<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\CacheInvalidator;

use App\Web\Service\CacheInvalidator\AlbumsCacheInvalidatorService;
use App\Web\Service\Trait\CacheRegisterTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

/**
 * @internal
 */
#[CoversClass(AlbumsCacheInvalidatorService::class)]
#[CoversTrait(CacheRegisterTrait::class)]
class AlbumsCacheInvalidatorServiceTest extends TestCase
{
    public function testInvalidate(): void
    {
        $cache = new ArrayAdapter();
        $cache->get('douze', fn () => 'DouZe');
        $cache->get('album_home_123', fn () => 'whatever');
        $cache->get('album_home_456', fn () => 'whatever');
        $cache->get('register_album', fn () => ['album_home_123']);

        $service = new AlbumsCacheInvalidatorService($cache);
        $service->invalidate();

        $values = $cache->getValues();
        $this->assertCount(2, $values);
        $this->assertArrayHasKey('douze', $values);
        $this->assertArrayHasKey('album_home_456', $values);
    }

    public function testInvalidateMock(): void
    {
        $cache = $this->createMock(ArrayAdapter::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->with('register_album')
            ->willReturnCallback(function ($key, $callback) {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with('register_album')
        ;

        $service = new AlbumsCacheInvalidatorService($cache);
        $service->invalidate();
    }

    public function testInvalidateMockNotArray(): void
    {
        $cache = $this->createMock(ArrayAdapter::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->with('register_album')
            ->willReturn('not_an_array')
        ;
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with('register_album')
        ;

        $service = new AlbumsCacheInvalidatorService($cache);
        $service->invalidate();
    }
}
