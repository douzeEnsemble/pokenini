<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\Entity\Pokemon;
use App\Api\Repository\GameBundlesShiniesAvailabilitiesRepository;
use App\Api\Service\GameBundlesShiniesAvailabilitiesService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class GameBundlesShiniesAvailabilitiesServiceTest extends TestCase
{
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
