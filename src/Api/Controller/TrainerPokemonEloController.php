<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\TrainerPokemonEloQueryOptions;
use App\Api\Repository\TrainerPokemonEloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/election')]
class TrainerPokemonEloController extends AbstractController
{
    #[Route(path: '/top', methods: ['GET'])]
    public function top(
        Request $request,
        TrainerPokemonEloRepository $trainerPokemonEloRepository,
    ): JsonResponse {
        $queryOptions = new TrainerPokemonEloQueryOptions($request->query->all());

        // Better with serializer ?
        return new JsonResponse(
            $trainerPokemonEloRepository->getTopN(
                $queryOptions->trainerExternalId,
                $queryOptions->electionSlug,
                $queryOptions->count,
            )
        );
    }
}
