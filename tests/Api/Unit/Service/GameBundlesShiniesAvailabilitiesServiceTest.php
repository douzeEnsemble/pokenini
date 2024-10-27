<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\DTO\GameBundlesShiniesAvailabilities;
use App\Api\Entity\Pokemon;
use App\Api\Repository\GameBundlesShiniesAvailabilitiesRepository;
use App\Api\Service\GameBundlesShiniesAvailabilitiesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @internal
 */
#[CoversClass(GameBundlesShiniesAvailabilitiesService::class)]
class GameBundlesShiniesAvailabilitiesServiceTest extends TestCase
{
    public function testGetFromPokemonWithCacheHit(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'pikachu';

        $expectedResult = $this->createMock(GameBundlesShiniesAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('gbsa-pikachu')
            ->willReturn($expectedResult)
        ;

        $repository = $this->createMock(GameBundlesShiniesAvailabilitiesRepository::class);
        $repository->expects($this->never())
            ->method('getFromPokemon')
        ;

        $service = new GameBundlesShiniesAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testGetFromPokemonWithCacheMiss(): void
    {
        $pokemon = $this->createMock(Pokemon::class);
        $pokemon->slug = 'charizard';

        $expectedResult = $this->createMock(GameBundlesShiniesAvailabilities::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache->method('get')
            ->with('gbsa-charizard')
            ->willReturnCallback(function (string $key, callable $callback): mixed {
                unset($key); // To remove PHPMD.UnusedFormalParameter warning

                return $callback();
            })
        ;

        $repository = $this->createMock(GameBundlesShiniesAvailabilitiesRepository::class);
        $repository->expects($this->once())
            ->method('getFromPokemon')
            ->willReturn($expectedResult)
        ;

        $service = new GameBundlesShiniesAvailabilitiesService($repository, $cache);

        $result = $service->getFromPokemon($pokemon);

        $this->assertSame($expectedResult, $result);
    }

    public function testCleanCacheFromPokemon(): void
    {
        $repository = $this->createMock(GameBundlesShiniesAvailabilitiesRepository::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('gbsa-azertyuiop'))
        ;

        $service = new GameBundlesShiniesAvailabilitiesService(
            $repository,
            $cache
        );

        $pokemon = new Pokemon();
        $pokemon->slug = 'azertyuiop';

        $service->cleanCacheFromPokemon($pokemon);
    }
}
