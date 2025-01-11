<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\TrainerPokemonEloListQueryOptions;
use App\Api\Repository\PokemonsRepository;

class GetNPokemonsToVoteService
{
    public function __construct(
        private readonly PokemonsRepository $pokemonsRepository,
        private readonly int $eloDefault,
        private readonly int $maxVotes,
        private readonly int $minVotes,
    ) {}

    /**
     * @return string[][]
     */
    public function getNPokemonsToVote(TrainerPokemonEloListQueryOptions $queryOptions): array
    {
        return $this->pokemonsRepository->getNToVote(
            $queryOptions->dexSlug,
            $queryOptions->count,
            $queryOptions->trainerExternalId,
            $queryOptions->electionSlug,
            $this->maxVotes,
            $this->minVotes,
            $this->eloDefault,
        );
    }
}
