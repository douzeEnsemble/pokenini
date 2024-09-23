<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Service;

use App\Api\Entity\Pokemon;
use App\Api\Repository\PokemonsRepository;
use App\Api\Service\CollectionsAvailabilitiesService;
use App\Tests\Api\Common\Traits\CounterTrait\CountCollectionAvailabilityTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(CollectionsAvailabilitiesService::class)]
class CollectionsAvailabilitiesServiceTest extends KernelTestCase
{
    use RefreshDatabaseTrait;
    use CountCollectionAvailabilityTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetFromPokemon(): void
    {
        /** @var CollectionsAvailabilitiesService $service */
        $service = static::getContainer()->get(CollectionsAvailabilitiesService::class);

        /** @var PokemonsRepository $pokemonsRepo */
        $pokemonsRepo = static::getContainer()->get(PokemonsRepository::class);

        /** @var Pokemon $pokemonDouze */
        $pokemonDouze = $pokemonsRepo->findOneBy(['name' => 'Douze']);

        $listDouze = $service->getFromPokemon($pokemonDouze);
        $this->assertFalse($listDouze->pogoshadow);
        $this->assertTrue($listDouze->swshdens);

        /** @var Pokemon $pokemonBulbasaur */
        $pokemonBulbasaur = $pokemonsRepo->findOneBy(['name' => 'Bulbasaur']);

        $listBulbasaur = $service->getFromPokemon($pokemonBulbasaur);
        $this->assertTrue($listBulbasaur->pogoshadow);
        $this->assertFalse($listBulbasaur->swshdens);

        /** @var Pokemon $pokemonDeoxys */
        $pokemonDeoxys = $pokemonsRepo->findOneBy(['name' => 'Deoxys']);

        $listDeoxys = $service->getFromPokemon($pokemonDeoxys);
        $this->assertNull($listDeoxys->pogoshadow);
        $this->assertNull($listDeoxys->swshdens);
    }
}
