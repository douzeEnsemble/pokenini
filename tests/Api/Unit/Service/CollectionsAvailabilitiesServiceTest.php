<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\DTO\CollectionsAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\CollectionsAvailabilitiesRepository;
use App\Api\Service\CollectionsAvailabilitiesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @internal
 */
#[CoversClass(CollectionsAvailabilitiesService::class)]
class CollectionsAvailabilitiesServiceTest extends TestCase
{
    public function testGetFromPokemonWithCacheHit(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'pikachu';

        $expectedResult = $this->createMock(CollectionsAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('ca-pikachu')
            ->willReturn($expectedResult)
        ;

        $repository = $this->createMock(CollectionsAvailabilitiesRepository::class);
        $repository->expects($this->never())
            ->method('getFromPokemon')
        ;

        $service = new CollectionsAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testGetFromPokemonWithCacheMiss(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'charizard';

        $expectedResult = $this->createMock(CollectionsAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('ca-charizard')
            ->willReturnCallback(function ($key, $callback) {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;

        $repository = $this->createMock(CollectionsAvailabilitiesRepository::class);
        $repository->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn($expectedResult)
        ;

        $service = new CollectionsAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testCleanCacheFromPokemon(): void
    {
        $repository = $this->createMock(CollectionsAvailabilitiesRepository::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('ca-azertyuiop'))
        ;

        $service = new CollectionsAvailabilitiesService(
            $repository,
            $cache
        );

        $pokemon = new Pokemon();
        $pokemon->slug = 'azertyuiop';

        $service->cleanCacheFromPokemon($pokemon);
    }
}
