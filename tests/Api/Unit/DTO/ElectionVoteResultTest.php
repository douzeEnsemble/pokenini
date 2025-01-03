<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\DTO;

use App\Api\DTO\ElectionVote;
use App\Api\DTO\ElectionVoteResult;
use App\Api\DTO\PokemonElo;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ElectionVoteResult::class)]
class ElectionVoteResultTest extends TestCase
{
    public function testOk(): void
    {
        $electionVote = new ElectionVote([
            'trainer_external_id' => '67865468',
            'election_slug' => 'douze',
            'winners_slugs' => ['pikachu'],
            'losers_slugs' => ['pichu', 'raichu'],
        ]);

        $pokemonsElo = [
            'winners' => [
                new PokemonElo('pikachu', 15),
            ],
            'losers' => [
                new PokemonElo('pichu', 0),
                new PokemonElo('raichu', -8000),
            ],
        ];

        $object = new ElectionVoteResult(
            $electionVote,
            $pokemonsElo,
            3,
        );

        $this->assertSame($electionVote, $object->getElectionVote());
        $this->assertSame($pokemonsElo, $object->getPokemonsElo());
        $this->assertSame(3, $object->getVoteCount());
    }
}
