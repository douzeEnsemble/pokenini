<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\Repository\TrainerPokemonEloRepository;

class UpdateTrainerPokemonEloService
{
    public function __construct(
        private readonly TrainerPokemonEloRepository $repository,
        private int $kFactor,
    ) {}

    public function updateElo(
        string $trainerExternalId,
        string $electionSlug,
        string $winnerSlug,
        string $loserSlug,
    ): void {
        $winnerElo = $this->repository->getElo($trainerExternalId, $electionSlug, $winnerSlug);
        $loserElo = $this->repository->getElo($trainerExternalId, $electionSlug, $loserSlug);

        $expectedWinnerElo = 1 / (1 + pow(10, ($loserElo - $winnerElo) / 400));
        $expectedLoserElo = 1 / (1 + pow(10, ($winnerElo - $loserElo) / 400));

        /** @var int $newWinnerElo */
        $newWinnerElo = (int) ($winnerElo + round($this->kFactor * (1 - $expectedWinnerElo)));

        /** @var int $newLoserElo */
        $newLoserElo = (int) ($loserElo + round($this->kFactor * (0 - $expectedLoserElo)));

        $this->repository->updateElo($newWinnerElo, $trainerExternalId, $electionSlug, $winnerSlug);
        $this->repository->updateElo($newLoserElo, $trainerExternalId, $electionSlug, $loserSlug);
    }
}
