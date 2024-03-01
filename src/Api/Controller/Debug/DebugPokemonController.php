<?php

declare(strict_types=1);

namespace App\Api\Controller\Debug;

use App\Api\Entity\Pokemon;
use App\Api\Service\GameBundlesAvailabilitiesService;
use App\Api\Service\GameBundlesShiniesAvailabilitiesService;
use App\Api\Service\GamesAvailabilitiesService;
use App\Api\Service\GamesShiniesAvailabilitiesService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/debogage/pokemon')]
class DebugPokemonController extends AbstractDebugController
{
    #[Route(path: '/{slug}', methods: ['GET'])]
    public function pokemon(
        Pokemon $pokemon,
    ): Response {
        return new Response(
            $this->serialize($pokemon),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json'
            ]
        );
    }

    #[Route(path: '/{slug}/caches', methods: ['DELETE'])]
    public function pokemonCaches(
        GamesAvailabilitiesService $gamesAvailabilitiesService,
        GamesShiniesAvailabilitiesService $gamesShiniesAvailabilitiesService,
        GameBundlesAvailabilitiesService $gameBundlesAvailabilitiesService,
        GameBundlesShiniesAvailabilitiesService $gameBundlesShiniesAvailabilitiesService,
        Pokemon $pokemon,
    ): Response {
        $gamesAvailabilitiesService->cleanCacheFromPokemon($pokemon);
        $gamesShiniesAvailabilitiesService->cleanCacheFromPokemon($pokemon);
        $gameBundlesAvailabilitiesService->cleanCacheFromPokemon($pokemon);
        $gameBundlesShiniesAvailabilitiesService->cleanCacheFromPokemon($pokemon);

        return new Response();
    }

    #[Route(path: '/{slug}/availabilities', methods: ['GET'])]
    public function pokemonAvailabilities(
        GamesAvailabilitiesService $gamesAvailabilitiesService,
        GamesShiniesAvailabilitiesService $gamesShiniesAvailabilitiesService,
        GameBundlesAvailabilitiesService $gameBundlesAvailabilitiesService,
        GameBundlesShiniesAvailabilitiesService $gameBundlesShiniesAvailabilitiesService,
        Pokemon $pokemon,
    ): Response {
        $gamesAvailabilities = $gamesAvailabilitiesService->getFromPokemon($pokemon);
        $gamesShiniesAvailabilities = $gamesShiniesAvailabilitiesService->getFromPokemon($pokemon);
        $gameBundlesAvailabilities = $gameBundlesAvailabilitiesService->getFromPokemon($pokemon);
        $gameBundlesShiniesAvailabilities = $gameBundlesShiniesAvailabilitiesService->getFromPokemon($pokemon);

        return new Response(
            $this->serialize([
                'gamesAvailabilities' => $gamesAvailabilities->all(),
                'gamesShiniesAvailabilities' => $gamesShiniesAvailabilities->all(),
                'gameBundlesAvailabilities' => $gameBundlesAvailabilities->all(),
                'gameBundlesShiniesAvailabilities' => $gameBundlesShiniesAvailabilities->all(),
            ]),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }
}
