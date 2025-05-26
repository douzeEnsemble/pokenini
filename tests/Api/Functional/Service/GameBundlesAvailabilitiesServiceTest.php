<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service;

use App\Api\Entity\Pokemon;
use App\Api\Repository\PokemonsRepository;
use App\Api\Service\GameBundlesAvailabilitiesService;
use App\Tests\Api\Common\Traits\CounterTrait\CountGameBundleAvailabilityTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(GameBundlesAvailabilitiesService::class)]
class GameBundlesAvailabilitiesServiceTest extends KernelTestCase
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
        /** @var GameBundlesAvailabilitiesService $service */
        $service = static::getContainer()->get(GameBundlesAvailabilitiesService::class);

        /** @var PokemonsRepository $pokemonsRepo */
        $pokemonsRepo = static::getContainer()->get(PokemonsRepository::class);

        /** @var Pokemon $pokemonDouze */
        $pokemonDouze = $pokemonsRepo->findOneBy(['name' => 'Douze']);

        $listDouze = $service->getFromPokemon($pokemonDouze);
        $this->assertTrue($listDouze->redgreenblueyellow);
        $this->assertFalse($listDouze->goldsilvercrystal);

        /** @var Pokemon $pokemonBulbasaur */
        $pokemonBulbasaur = $pokemonsRepo->findOneBy(['name' => 'Bulbasaur']);

        $listBulbasaur = $service->getFromPokemon($pokemonBulbasaur);
        $this->assertTrue($listBulbasaur->redgreenblueyellow);
        $this->assertTrue($listBulbasaur->goldsilvercrystal);

        /** @var Pokemon $pokemonMegaVenusaur */
        $pokemonMegaVenusaur = $pokemonsRepo->findOneBy(['name' => 'Mega Venusaur']);

        $listMegaVenusaur = $service->getFromPokemon($pokemonMegaVenusaur);
        $this->assertFalse($listMegaVenusaur->redgreenblueyellow);
        $this->assertFalse($listMegaVenusaur->goldsilvercrystal);
    }
}
