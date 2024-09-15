<?php

declare(strict_types=1);

namespace App\Api\Calculator\PokemonAvailabilities;

use App\Api\Calculator\AbstractCalculator;
use App\Api\Entity\PokemonAvailabilities;
use App\Api\Repository\PokemonAvailabilitiesRepository;

class GameBundlesCalculator extends AbstractCalculator
{
    protected string $statisticName = 'pokemon_availabilities_game_bundle';

    public function __construct(
        private readonly PokemonAvailabilitiesRepository $repository,
    ) {}

    public function execute(): void
    {
        $this->init();

        $this->repository->removeAllByCategory(PokemonAvailabilities::CATEGORY_GAME_BUNDLE);

        $count = $this->repository->calculateGameBundle();

        $this->statictic->incrementBy($count);
    }
}
