<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\ElectionVote;
use App\Api\DTO\PokemonElo;
use App\Api\DTO\UpdatedTrainerPokemonElo;
use App\Api\Repository\TrainerPokemonEloRepository;

class ElectionService
{
    public function __construct(
        private readonly TrainerPokemonEloRepository $repository,
        private int $eloDefault,
        private int $eloKFactor,
        private int $eloDDifference,
    ) {}

    /**
     * @return UpdatedTrainerPokemonElo[]
     */
    public function update(ElectionVote $electionVote): array
    {
        $winnerElo = $this->getWinnerElo($electionVote);

        $losersPokemonElo = $this->getLosersElo($electionVote);

        $losersElo = [];
        foreach ($losersPokemonElo as $pokemonElo) {
            $losersElo[] = $pokemonElo->getElo();
        }

        $losersAverageElo = round(array_sum($losersElo) / count($losersElo));

        $expectedWinnerElo = 1 / (1 + pow(10, ($losersAverageElo - $winnerElo) / $this->eloDDifference));
        $expectedLosersElo = 1 - $expectedWinnerElo;

        $newWinnerElo = (int) ($winnerElo + round($this->eloKFactor * (1 - $expectedWinnerElo)));

        $results = [];
        foreach ($losersPokemonElo as $pokemonElo) {
            $newLoserElo = (int) ($pokemonElo->getElo() + round($this->eloKFactor * (0 - $expectedLosersElo)));

            $this->repository->updateElo(
                $newLoserElo,
                $electionVote->trainerExternalId,
                $electionVote->electionSlug,
                $pokemonElo->getPokemonSlug()
            );

            $results[] = new UpdatedTrainerPokemonElo(
                $electionVote->winnerSlug,
                $newWinnerElo,
                $pokemonElo->getPokemonSlug(),
                $newLoserElo
            );
        }

        $this->repository->updateElo(
            $newWinnerElo,
            $electionVote->trainerExternalId,
            $electionVote->electionSlug,
            $electionVote->winnerSlug,
        );

        return $results;
    }

    private function getWinnerElo(ElectionVote $electionVote): int
    {
        $winnerElo = $this->repository->getElo(
            $electionVote->trainerExternalId,
            $electionVote->electionSlug,
            $electionVote->winnerSlug
        );

        return $winnerElo ?? $this->eloDefault;
    }

    /**
     * @return PokemonElo[]
     */
    private function getLosersElo(ElectionVote $electionVote): array
    {
        $losersElo = [];
        foreach ($electionVote->losersSlugs as $loserSlug) {
            $loserElo = $this->repository->getElo(
                $electionVote->trainerExternalId,
                $electionVote->electionSlug,
                $loserSlug
            );
            $loserElo ??= $this->eloDefault;

            $losersElo[] = new PokemonElo($loserSlug, $loserElo);
        }

        return $losersElo;
    }
}
