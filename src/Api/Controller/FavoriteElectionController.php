<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Service\UpdateTrainerPokemonEloService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/favorite')]
class FavoriteElectionController extends AbstractController
{
    #[Route(path: '', methods: ['GET'])]
    public function get(
        UpdateTrainerPokemonEloService $updateTrainerPokemonEloService
    ): JsonResponse {
        return new JsonResponse();
    }
}
