<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\DTO;

use App\Web\DTO\ElectionVoteResult;
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
        $result = ElectionVoteResult::createFromApi([
            'electionVote' => [
                'trainerExternalId' => '7b52009b64fd0a2a49e6d8a939753077792b0554',
                'electionSlug' => '',
                'winnersSlugs' => [
                    'butterfree',
                ],
                'losersSlugs' => [
                    'caterpie',
                    'metapod',
                ],
            ],
            'pokemonsElo' => [
                'winners' => [
                    [
                        'pokemonSlug' => 'butterfree',
                        'elo' => 1016,
                    ],
                ],
                'losers' => [
                    [
                        'pokemonSlug' => 'caterpie',
                        'elo' => 984,
                    ],
                    [
                        'pokemonSlug' => 'metapod',
                        'elo' => 984,
                    ],
                ],
            ],
            'voteCount' => 3,
        ]);

        $this->assertSame(3, $result->getVoteCount());
    }
}
