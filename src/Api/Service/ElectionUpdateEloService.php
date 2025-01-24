<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\ElectionVote;
use App\Api\DTO\PokemonElo;
use App\Api\Repository\TrainerPokemonEloRepository;

class ElectionUpdateEloService
{
    public function __construct(
        private readonly TrainerPokemonEloRepository $repository,
        private int $eloDefault,
        private int $eloKFactor,
        private int $eloDDifference,
    ) {}

    /**
     * @return PokemonElo[][]
     */
    public function update(ElectionVote $electionVote): array
    {
        $winnersPokemonElo = $this->getPokemonsElo($electionVote, $electionVote->winnersSlugs);
        $losersPokemonElo = $this->getPokemonsElo($electionVote, $electionVote->losersSlugs);

        $winnersAverageElo = $this->getAverageElo($winnersPokemonElo);
        $losersAverageElo = $this->getAverageElo($losersPokemonElo);

        $expectedWinnerElo = 1 / (1 + pow(10, ($losersAverageElo - $winnersAverageElo) / $this->eloDDifference));
        $expectedLoserElo = 1 - $expectedWinnerElo;

        $results = [];
        $results['winners'] = $this->updatePokemonsElo($electionVote, $winnersPokemonElo, 1 - $expectedWinnerElo);
        $results['losers'] = $this->updatePokemonsElo($electionVote, $losersPokemonElo, 0 - $expectedLoserElo);

        return $results;
    }

    /**
     * @param string[] $list
     *
     * @return PokemonElo[]
     */
    private function getPokemonsElo(ElectionVote $electionVote, array $list): array
    {
        $pokemonsElo = [];
        foreach ($list as $slug) {
            $elo = $this->repository->getElo(
                $electionVote->trainerExternalId,
                $electionVote->dexSlug,
                $electionVote->electionSlug,
                $slug,
            );
            $elo ??= $this->eloDefault;

            $pokemonsElo[] = new PokemonElo($slug, $elo);
        }

        return $pokemonsElo;
    }

    /**
     * @param PokemonElo[] $pokemonsElo
     */
    private function getAverageElo(array $pokemonsElo): int
    {
        $listElo = [];
        foreach ($pokemonsElo as $pokemonElo) {
            $listElo[] = $pokemonElo->getElo();
        }

        if (empty($listElo)) {
            return $this->eloDefault;
        }

        return (int) round(array_sum($listElo) / count($listElo));
    }

    /**
     * @param PokemonElo[] $list
     *
     * @return PokemonElo[]
     */
    private function updatePokemonsElo(ElectionVote $electionVote, array $list, float $baseExpectedElo): array
    {
        $result = [];
        foreach ($list as $pokemonElo) {
            $newElo = $this->updatePokemonElo($electionVote, $pokemonElo, $baseExpectedElo);

            $result[] = new PokemonElo($pokemonElo->getPokemonSlug(), $newElo);
        }

        return $result;
    }

    private function updatePokemonElo(ElectionVote $electionVote, PokemonElo $pokemonElo, float $baseExpectedElo): int
    {
        $newElo = (int) ($pokemonElo->getElo() + round($this->eloKFactor * $baseExpectedElo));

        $this->repository->updateElo(
            $newElo,
            $electionVote->trainerExternalId,
            $electionVote->dexSlug,
            $electionVote->electionSlug,
            $pokemonElo->getPokemonSlug(),
            $newElo > $pokemonElo->getElo(),
        );

        return $newElo;
    }
}
