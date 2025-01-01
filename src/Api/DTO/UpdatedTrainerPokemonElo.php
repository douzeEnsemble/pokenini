<?php

declare(strict_types=1);

namespace App\Api\DTO;

final class UpdatedTrainerPokemonElo
{
    public function __construct(
        private readonly int $winnerElo,
        private readonly int $loserElo
    ) {}

    public function getWinnerElo(): int
    {
        return $this->winnerElo;
    }

    public function getLoserElo(): int
    {
        return $this->loserElo;
    }
}
