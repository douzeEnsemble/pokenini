<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\Entity\Pokemon;
use App\Api\Repository\GamesShiniesAvailabilitiesRepository;
use App\Api\Service\GamesShiniesAvailabilitiesService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class GamesShiniesAvailabilitiesServiceTest extends TestCase
{
    public function testCleanCacheFromPokemon(): void
    {
        $repository = $this->createMock(GamesShiniesAvailabilitiesRepository::class);

        $cache = $this->createMock(CacheInterface::class);
        $cache
            ->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('gsa-azertyuiop'));
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
