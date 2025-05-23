<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\TrainerPokemonEloQueryOptions;
use App\Api\DTO\TrainerPokemonEloTopQueryOptions;
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
        $queryOptions = new TrainerPokemonEloTopQueryOptions($request->query->all());

        // Better with serializer ?
        return new JsonResponse(
            $trainerPokemonEloRepository->getTopN(
                $queryOptions->trainerExternalId,
                $queryOptions->dexSlug,
                $queryOptions->electionSlug,
                $queryOptions->count,
            )
        );
    }

    #[Route(path: '/metrics', methods: ['GET'])]
    public function metrics(
        Request $request,
        TrainerPokemonEloRepository $trainerPokemonEloRepository,
    ): JsonResponse {
        $queryOptions = new TrainerPokemonEloQueryOptions($request->query->all());

        // Better with serializer ?
        return new JsonResponse(
            $trainerPokemonEloRepository->getMetrics(
                $queryOptions->trainerExternalId,
                $queryOptions->dexSlug,
                $queryOptions->electionSlug,
            )
        );
    }
}
