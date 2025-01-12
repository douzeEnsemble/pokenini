<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\ElectionPokemonsList;
use App\Api\DTO\TrainerPokemonEloListQueryOptions;

class GetNPokemonsToChooseService
{
    public function __construct(
        private readonly GetNPokemonsToPickService $toPickService,
        private readonly GetNPokemonsToVoteService $toVoteService,
    ) {}

    public function getNPokemonsToChoose(TrainerPokemonEloListQueryOptions $queryOptions): ElectionPokemonsList
    {
        $toPick = $this->toPickService->getNPokemonsToPick($queryOptions);

        if (!empty($toPick)) {
            return new ElectionPokemonsList('pick', $toPick);
        }

        return new ElectionPokemonsList(
            'vote',
            $this->toVoteService->getNPokemonsToVote($queryOptions)
        );
    }
}
