<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\UpdatedTrainerPokemonElo;
use App\Api\Repository\TrainerPokemonEloRepository;

class UpdateTrainerPokemonEloService
{
    public function __construct(
        private readonly TrainerPokemonEloRepository $repository,
        private int $eloKFactor,
        private int $eloDDifference,
    ) {}

    public function updateElo(
        string $trainerExternalId,
        string $electionSlug,
        string $winnerSlug,
        int $winnerElo,
        string $loserSlug,
        int $loserElo,
    ): UpdatedTrainerPokemonElo {
        $expectedWinnerElo = 1 / (1 + pow(10, ($loserElo - $winnerElo) / $this->eloDDifference));
        $expectedLoserElo = 1 / (1 + pow(10, ($winnerElo - $loserElo) / $this->eloDDifference));

        /** @var int $newWinnerElo */
        $newWinnerElo = (int) ($winnerElo + round($this->eloKFactor * (1 - $expectedWinnerElo)));

        /** @var int $newLoserElo */
        $newLoserElo = (int) ($loserElo + round($this->eloKFactor * (0 - $expectedLoserElo)));

        $this->repository->updateElo($newWinnerElo, $trainerExternalId, $electionSlug, $winnerSlug);
        $this->repository->updateElo($newLoserElo, $trainerExternalId, $electionSlug, $loserSlug);

        return new UpdatedTrainerPokemonElo($winnerSlug, $newWinnerElo, $loserSlug, $newLoserElo);
    }
}
