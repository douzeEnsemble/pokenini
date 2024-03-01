<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\Entity\Pokemon;
use App\Api\Repository\GamesAvailabilitiesRepository;
use App\Api\Service\GamesAvailabilitiesService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class GamesAvailabilitiesServiceTest extends TestCase
{
    public function testCleanCacheFromPokemon(): void
    {
        $repository = $this->createMock(GamesAvailabilitiesRepository::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('ga-azertyuiop'));
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
