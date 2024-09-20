<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\DTO\GamesAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GamesAvailabilitiesRepository;
use App\Api\Service\GamesAvailabilitiesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @internal
 */
#[CoversClass(GamesAvailabilitiesService::class)]
class GamesAvailabilitiesServiceTest extends TestCase
{
    public function testGetFromPokemonWithCacheHit(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'pikachu';

        $expectedResult = $this->createMock(GamesAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('ga-pikachu')
            ->willReturn($expectedResult)
        ;

        $repository = $this->createMock(GamesAvailabilitiesRepository::class);
        $repository->expects($this->never())
            ->method('getFromPokemon')
        ;

        $service = new GamesAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testGetFromPokemonWithCacheMiss(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'charizard';

        $expectedResult = $this->createMock(GamesAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('ga-charizard')
            ->willReturnCallback(function ($key, $callback) {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;

        $repository = $this->createMock(GamesAvailabilitiesRepository::class);
        $repository->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn($expectedResult)
        ;

        $service = new GamesAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testCleanCacheFromPokemon(): void
    {
        $repository = $this->createMock(GamesAvailabilitiesRepository::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('ga-azertyuiop'))
        ;

        $service = new GamesAvailabilitiesService(
            $repository,
            $cache
        );

        $pokemon = new Pokemon();
        $pokemon->slug = 'azertyuiop';

        $service->cleanCacheFromPokemon($pokemon);
    }
}
