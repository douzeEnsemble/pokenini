<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\ActionStarter\UpdateCollectionsAvailabilitiesActionStarter;
use App\Api\ActionStarter\UpdateGamesAvailabilitiesActionStarter;
use App\Api\ActionStarter\UpdateGamesCollectionsAndDexActionStarter;
use App\Api\ActionStarter\UpdateGamesShiniesAvailabilitiesActionStarter;
use App\Api\ActionStarter\UpdateLabelsActionStarter;
use App\Api\ActionStarter\UpdatePokemonsActionStarter;
use App\Api\ActionStarter\UpdateRegionalDexNumbersActionStarter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/istration/update')]
class AdminUpdateController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    #[Route(path: '/labels', methods: ['POST'])]
    public function updateLabels(
        UpdateLabelsActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/games_collections_and_dex', methods: ['POST'])]
    public function updateGamesCollectionsAndDex(
        UpdateGamesCollectionsAndDexActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/pokemons', methods: ['POST'])]
    public function updatePokemons(
        UpdatePokemonsActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/regional_dex_numbers', methods: ['POST'])]
    public function updateRegionalDexNumbers(
        UpdateRegionalDexNumbersActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/games_availabilities', methods: ['POST'])]
    public function updateGamesAvailabilities(
        UpdateGamesAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/games_shinies_availabilities', methods: ['POST'])]
    public function updateGamesShiniesAvailabilities(
        UpdateGamesShiniesAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }

    #[Route(path: '/collections_availabilities', methods: ['POST'])]
    public function updateCollectionsAvailabilities(
        UpdateCollectionsAvailabilitiesActionStarter $actionStarter
    ): Response {
        $message = $actionStarter->start();

        $this->bus->dispatch($message);

        return new Response('', Response::HTTP_CREATED);
    }
}
