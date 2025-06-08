<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service;

use App\Api\Entity\Pokemon;
use App\Api\Repository\PokemonsRepository;
use App\Api\Service\GamesShiniesAvailabilitiesService;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameBundleAvailabilityTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(GamesShiniesAvailabilitiesService::class)]
class GamesShiniesAvailabilitiesServiceTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountGameBundleAvailabilityTrait;

    #[\Override]
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetFromPokemon(): void
    {
        /** @var GamesShiniesAvailabilitiesService $service */
        $service = static::getContainer()->get(GamesShiniesAvailabilitiesService::class);

        /** @var PokemonsRepository $pokemonsRepo */
        $pokemonsRepo = static::getContainer()->get(PokemonsRepository::class);

        /** @var Pokemon $pokemonDouze */
        $pokemonDouze = $pokemonsRepo->findOneBy(['name' => 'Douze']);

        $listDouze = $service->getFromPokemon($pokemonDouze);
        $this->assertNull($listDouze->red);
        $this->assertNull($listDouze->blue);
        $this->assertNull($listDouze->emerald);

        /** @var Pokemon $pokemonBulbasaur */
        $pokemonBulbasaur = $pokemonsRepo->findOneBy(['name' => 'Bulbasaur']);

        $listBulbasaur = $service->getFromPokemon($pokemonBulbasaur);
        $this->assertTrue($listBulbasaur->red);
        $this->assertTrue($listBulbasaur->blue);
        $this->assertNull($listBulbasaur->emerald);

        /** @var Pokemon $pokemonDeoxys */
        $pokemonDeoxys = $pokemonsRepo->findOneBy(['name' => 'Deoxys']);

        $listDeoxys = $service->getFromPokemon($pokemonDeoxys);
        $this->assertNull($listDeoxys->red);
        $this->assertTrue($listDeoxys->ruby);
        $this->assertFalse($listDeoxys->emerald);
    }
}
