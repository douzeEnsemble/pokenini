<?php

declare(strict_types=1);

namespace App\Api\Calculator;

use App\Api\Entity\Dex;
use App\Api\Entity\DexAvailability;
use App\Api\Entity\Pokemon;
use App\Api\Service\CollectionsAvailabilitiesService;
use App\Api\Service\GameBundlesAvailabilitiesService;
use App\Api\Service\GameBundlesShiniesAvailabilitiesService;
use App\Api\Service\GamesAvailabilitiesService;
use App\Api\Service\GamesShiniesAvailabilitiesService;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class DexPokemonAvailabilityCalculator
{
    private readonly ExpressionLanguage $expressionLanguage;

    public function __construct(
        private readonly GameBundlesAvailabilitiesService $gameBundlesAvailabilitiesService,
        private readonly GameBundlesShiniesAvailabilitiesService $gameBundlesShiniesAvailabilitiesService,
        private readonly GamesAvailabilitiesService $gamesAvailabilitiesService,
        private readonly GamesShiniesAvailabilitiesService $gamesShiniesAvailabilitiesService,
        private readonly CollectionsAvailabilitiesService $collectionsAvailabilitiesService,
    ) {
        $this->expressionLanguage = new ExpressionLanguage();
    }

    public function calculate(Dex $dex, Pokemon $pokemon): ?DexAvailability
    {
        $rule = $dex->selectionRule;

        $values = $this->getValues($rule, $pokemon);

        $isGettable = $this->expressionLanguage->evaluate($rule, $values);

        if (!$isGettable) {
            return null;
        }

        return DexAvailability::create($pokemon, $dex);
    }

    /**
     * @return mixed[][]
     */
    private function getValues(string $rule, Pokemon $pokemon): array
    {
        $values = [];

        $this->setPokemonValues($values, $rule, $pokemon);
        $this->setBundlesValues($values, $rule, $pokemon);
        $this->setGamesValues($values, $rule, $pokemon);
        $this->setCollectionsValues($values, $rule, $pokemon);

        return $values;
    }

    /**
     * @param mixed[] $values
     */
    private function setPokemonValues(array &$values, string $rule, Pokemon $pokemon): void
    {
        if (str_contains($rule, 'p.') || str_contains($rule, 'p?.')) {
            $values['p'] = $pokemon;
        }
    }

    /**
     * @param mixed[] $values
     */
    private function setBundlesValues(array &$values, string $rule, Pokemon $pokemon): void
    {
        if (str_contains($rule, 'ba.') || str_contains($rule, 'ba?.')) {
            $values['ba'] = $this->gameBundlesAvailabilitiesService->getFromPokemon($pokemon);
        }

        if (str_contains($rule, 'bsa.') || str_contains($rule, 'bsa?.')) {
            $values['bsa'] = $this->gameBundlesShiniesAvailabilitiesService->getFromPokemon($pokemon);
        }
    }

    /**
     * @param mixed[] $values
     */
    private function setGamesValues(array &$values, string $rule, Pokemon $pokemon): void
    {
        if (str_contains($rule, 'ga.') || str_contains($rule, 'ga?.')) {
            $values['ga'] = $this->gamesAvailabilitiesService->getFromPokemon($pokemon);
        }

        if (str_contains($rule, 'gsa.') || str_contains($rule, 'gsa?.')) {
            $values['gsa'] = $this->gamesShiniesAvailabilitiesService->getFromPokemon($pokemon);
        }
    }

    /**
     * @param mixed[] $values
     */
    private function setCollectionsValues(array &$values, string $rule, Pokemon $pokemon): void
    {
        if (str_contains($rule, 'ca.') || str_contains($rule, 'ca?.')) {
            $values['ca'] = $this->collectionsAvailabilitiesService->getFromPokemon($pokemon);
        }
    }
}
