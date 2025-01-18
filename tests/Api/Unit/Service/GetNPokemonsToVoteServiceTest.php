<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Service;

use App\Api\DTO\TrainerPokemonEloListQueryOptions;
use App\Api\Repository\PokemonsRepository;
use App\Api\Service\GetNPokemonsToVoteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(GetNPokemonsToVoteService::class)]
class GetNPokemonsToVoteServiceTest extends TestCase
{
    public function testGetNPokemonsToVote(): void
    {
        $queryOptions = new TrainerPokemonEloListQueryOptions([
            'trainer_external_id' => 'bd307a3ec329e10a2cff8fb87480823da114f8f4',
            'dex_slug' => 'demo',
            'election_slug' => 'pref',
            'count' => 12,
        ]);

        $repository = $this->createMock(PokemonsRepository::class);
        $repository->expects($this->once())
            ->method('getNToVote')
            ->with(
                'demo',
                12,
                'bd307a3ec329e10a2cff8fb87480823da114f8f4',
                'pref',
                $queryOptions->albumFilters,
                1000
            )
            ->willReturn(
                [
                    [
                        'toto',
                    ],
                    [
                        'titi',
                    ],
                ],
            )
        ;

        $service = new GetNPokemonsToVoteService(
            $repository,
            1000,
        );

        $this->assertEquals(
            [
                [
                    'toto',
                ],
                [
                    'titi',
                ],
            ],
            $service->getNPokemonsToVote($queryOptions)
        );
    }
}
