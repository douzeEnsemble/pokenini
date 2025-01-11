<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\TrainerPokemonEloListQueryOptions;
use App\Api\Service\GetNPokemonsToVoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pokemons')]
class PokemonsController extends AbstractController
{
    #[Route(path: '/list', methods: ['GET'])]
    public function getNPokemonsToVote(
        Request $request,
        GetNPokemonsToVoteService $getService,
    ): JsonResponse {
        $queryOptions = new TrainerPokemonEloListQueryOptions($request->query->all());

        $list = $getService->getNPokemonsToVote($queryOptions);

        // Better with serializer ?
        return new JsonResponse($list);
    }
}
