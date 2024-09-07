<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\ActionStarter\CalculateDexAvailabilitiesActionStarter;
use App\Api\ActionStarter\CalculateGameBundlesAvailabilitiesActionStarter;
use App\Api\ActionStarter\CalculateGameBundlesShiniesAvailabilitiesActionStarter;
use App\Api\ActionStarter\CalculatePokemonAvailabilitiesActionStarter;
use App\Api\ActionStarter\UpdateGamesAndDexActionStarter;
use App\Api\ActionStarter\UpdateGamesAvailabilitiesActionStarter;
use App\Api\ActionStarter\UpdateGamesShiniesAvailabilitiesActionStarter;
use App\Api\ActionStarter\UpdateLabelsActionStarter;
use App\Api\ActionStarter\UpdatePokemonsActionStarter;
use App\Api\ActionStarter\UpdateRegionalDexNumbersActionStarter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/istration')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    #[Route(path: '/update/labels', methods: ['POST'])]
    public function updateLabels(
        UpdateLabelsActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/update/games_and_dex', methods: ['POST'])]
    public function updateGamesAndDex(
        UpdateGamesAndDexActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/update/pokemons', methods: ['POST'])]
    public function updatePokemons(
        UpdatePokemonsActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/update/regional_dex_numbers', methods: ['POST'])]
    public function updateRegionalDexNumbers(
        UpdateRegionalDexNumbersActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/update/games_availabilities', methods: ['POST'])]
    public function updateGamesAvailabilities(
        UpdateGamesAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/update/games_shinies_availabilities', methods: ['POST'])]
    public function updateGamesShiniesAvailabilities(
        UpdateGamesShiniesAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/calculate/game_bundles_availabilities', methods: ['POST'])]
    public function calculateGameBundlesAvailabilities(
        CalculateGameBundlesAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/calculate/game_bundles_shinies_availabilities', methods: ['POST'])]
    public function calculateGameBundlesShiniesAvailabilities(
        CalculateGameBundlesShiniesAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/calculate/dex_availabilities', methods: ['POST'])]
    public function calculateDexAvailabilities(
        CalculateDexAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/calculate/pokemon_availabilities', methods: ['POST'])]
    public function calculatePokemonAvailabilities(
        CalculatePokemonAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }
}
