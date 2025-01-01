<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\ElectionVote;
use App\Api\Service\UpdateTrainerPokemonEloService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/election')]
class ElectionController extends AbstractController
{
    #[Route(path: '/vote', methods: ['POST'])]
    public function vote(
        Request $request,
        UpdateTrainerPokemonEloService $updateTrainerPokemonEloService,
    ): JsonResponse {
        $json = $request->getContent();

        if (!$json) {
            throw new BadRequestHttpException();
        }

        /** @var string[]|string[][] */
        $content = json_decode($json, true);

        try {
            $attributes = new ElectionVote($content);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $winnerElo = 0;
        $losersElo = [];
        foreach ($attributes->losersSlugs as $loserSlug) {
            $updatedElo = $updateTrainerPokemonEloService->updateElo(
                $attributes->trainerExternalId,
                $attributes->electionSlug,
                $attributes->winnerSlug,
                $loserSlug,
            );

            $winnerElo = $updatedElo->getWinnerElo();
            $losersElo[$loserSlug] = $updatedElo->getLoserElo();
        }

        // Better with serializer ?
        return new JsonResponse([
            'winnerFinalElo' => $winnerElo,
            'losersElo' => $losersElo,
        ]);
    }
}
