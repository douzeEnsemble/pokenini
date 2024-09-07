<?php

declare(strict_types=1);

namespace App\Api\Calculator;

use App\Api\Entity\Dex;
use App\Api\Entity\Pokemon;
use App\Api\Repository\PokemonsRepository;
use Doctrine\ORM\EntityManagerInterface;

class DexAvailabilityCalculator
{
    public function __construct(
        private readonly PokemonsRepository $pokemonsRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly DexPokemonAvailabilityCalculator $dexPokemonAvailabilityCalculator,
    ) {}

    public function calculate(Dex $dex): int
    {
        $count = 0;

        $pokemonQuery = $this->pokemonsRepository->getQueryAll();

        /** @var Pokemon $pokemon */
        foreach ($pokemonQuery->toIterable() as $pokemon) {
            $dexAvailability = $this->dexPokemonAvailabilityCalculator->calculate($dex, $pokemon);

            if (null === $dexAvailability) {
                continue;
            }

            $this->entityManager->persist($dexAvailability);

            ++$count;
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        return $count;
    }
}
