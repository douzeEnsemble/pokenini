<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\TrainerPokemonEloListQueryOptions;

class GetNPokemonsToChooseService
{
    public function __construct(
        private readonly GetNPokemonsToPickService $toPickService,
        private readonly GetNPokemonsToVoteService $toVoteService,
    ) {}

    /**
     * @return string[][]
     */
    public function getNPokemonsToChoose(TrainerPokemonEloListQueryOptions $queryOptions): array
    {
        $toPick = $this->toPickService->getNPokemonsToPick($queryOptions);

        if (!empty($toPick)) {
            return $toPick;
        }

        return $this->toVoteService->getNPokemonsToVote($queryOptions);
    }
}
