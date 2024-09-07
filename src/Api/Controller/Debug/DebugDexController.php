<?php

declare(strict_types=1);

namespace App\Api\Controller\Debug;

use App\Api\Entity\Dex;
use App\Api\Entity\DexAvailability;
use App\Api\Service\DexAvailabilitiesService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/debogage/dex')]
class DebugDexController extends AbstractDebugController
{
    #[Route(path: '/{slug}', methods: ['GET'])]
    public function dex(
        Dex $dex,
    ): Response {
        return new Response(
            $this->serialize($dex),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }

    #[Route(path: '/{slug}/availabilities', methods: ['GET'])]
    public function dexAvailabilities(
        Dex $dex,
        DexAvailabilitiesService $dexAvailabilitiesService,
    ): Response {
        $dexAvailabilities = $dexAvailabilitiesService->getByDex($dex);

        $pokemons = [];

        /** @var DexAvailability $dexAvailability */
        foreach ($dexAvailabilities as $dexAvailability) {
            $pokemons[] = $dexAvailability->pokemon->slug;
        }

        return new Response(
            $this->serialize($pokemons),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }
}
