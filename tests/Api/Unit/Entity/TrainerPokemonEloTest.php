<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\Pokemon;
use App\Api\Entity\TrainerPokemonElo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TrainerPokemonElo::class)]
class TrainerPokemonEloTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'Douze';

        $entity = new TrainerPokemonElo('121212', $pokemon, 'home');

        $this->assertSame('121212', $entity->getTrainerExternalId());
        $this->assertSame($pokemon, $entity->getPokemon());
        $this->assertSame(1000, $entity->elo);
        $this->assertSame('home', $entity->getDexSlug());
        $this->assertSame('', $entity->getElectionSlug());
    }

    public function testConstructorAndGettersWithElectionSlug(): void
    {
        $pokemon = new Pokemon();
        $pokemon->slug = 'Douze';

        $entity = new TrainerPokemonElo('121212', $pokemon, 'demo', 'pref');

        $this->assertSame('121212', $entity->getTrainerExternalId());
        $this->assertSame($pokemon, $entity->getPokemon());
        $this->assertSame(1000, $entity->elo);
        $this->assertSame('demo', $entity->getDexSlug());
        $this->assertSame('pref', $entity->getElectionSlug());
    }
}
