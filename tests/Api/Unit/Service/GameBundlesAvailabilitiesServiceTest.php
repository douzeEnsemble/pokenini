<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\Entity\Pokemon;
use App\Api\Repository\GameBundlesAvailabilitiesRepository;
use App\Api\Service\GameBundlesAvailabilitiesService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class GameBundlesAvailabilitiesServiceTest extends TestCase
{
    public function testCleanCacheFromPokemon(): void
    {
        $repository = $this->createMock(GameBundlesAvailabilitiesRepository::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('gba-azertyuiop'));
        ;

        $service = new GameBundlesAvailabilitiesService(
            $repository,
            $cache
        );

        $pokemon = new Pokemon();
        $pokemon->slug = 'azertyuiop';

        $service->cleanCacheFromPokemon($pokemon);
    }
}
