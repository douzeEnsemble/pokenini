<?php

declare(strict_types=1);

namespace App\Api\DTO;

final class UpdatedTrainerPokemonElo
{
    public function __construct(
        private readonly string $winnerSlug,
        private readonly int $winnerElo,
        private readonly string $loserSlug,
        private readonly int $loserElo
    ) {}

    public function getWinnerSlug(): string
    {
        return $this->winnerSlug;
    }

    public function getWinnerElo(): int
    {
        return $this->winnerElo;
    }

    public function getLoserSlug(): string
    {
        return $this->loserSlug;
    }

    public function getLoserElo(): int
    {
        return $this->loserElo;
    }
}
