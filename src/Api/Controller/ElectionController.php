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

#[Route('/favorite')]
class ElectionController extends AbstractController
{
    #[Route(path: 'vote', methods: ['POST'])]
    public function getN(
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

        foreach ($attributes->losersSlugs as $loserSlug) {
            $updateTrainerPokemonEloService->updateElo(
                $attributes->trainerExternalId,
                $attributes->electionSlug,
                $attributes->winnerSlug,
                $loserSlug,
            );
        }

        // Better with serializer ?
        return new JsonResponse();
    }
}
