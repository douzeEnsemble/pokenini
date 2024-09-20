<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\DTO\GamesShiniesAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GamesShiniesAvailabilitiesRepository;
use App\Api\Service\GamesShiniesAvailabilitiesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @internal
 */
#[CoversClass(GamesShiniesAvailabilitiesService::class)]
class GamesShiniesAvailabilitiesServiceTest extends TestCase
{
    public function testGetFromPokemonWithCacheHit(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'pikachu';

        $expectedResult = $this->createMock(GamesShiniesAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('gsa-pikachu')
            ->willReturn($expectedResult)
        ;

        $repository = $this->createMock(GamesShiniesAvailabilitiesRepository::class);
        $repository->expects($this->never())
            ->method('getFromPokemon')
        ;

        $service = new GamesShiniesAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testGetFromPokemonWithCacheMiss(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'charizard';

        $expectedResult = $this->createMock(GamesShiniesAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('gsa-charizard')
            ->willReturnCallback(function ($key, $callback) {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;

        $repository = $this->createMock(GamesShiniesAvailabilitiesRepository::class);
        $repository->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn($expectedResult)
        ;

        $service = new GamesShiniesAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testCleanCacheFromPokemon(): void
    {
        $repository = $this->createMock(GamesShiniesAvailabilitiesRepository::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('gsa-azertyuiop'))
        ;

        $service = new GamesShiniesAvailabilitiesService(
            $repository,
            $cache
        );

        $pokemon = new Pokemon();
        $pokemon->slug = 'azertyuiop';

        $service->cleanCacheFromPokemon($pokemon);
    }
}
