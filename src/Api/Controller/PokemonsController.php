<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\TrainerPokemonEloListQueryOptions;
use App\Api\Service\GetNPokemonsToPickService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pokemons')]
class PokemonsController extends AbstractController
{
    #[Route(path: '/to_pick', methods: ['GET'])]
    public function getNPokemonsToPick(
        Request $request,
        GetNPokemonsToPickService $getService,
    ): JsonResponse {
        $queryOptions = new TrainerPokemonEloListQueryOptions($request->query->all());

        $list = $getService->getNPokemonsToPick($queryOptions);

        // Better with serializer ?
        return new JsonResponse($list);
    }
}
