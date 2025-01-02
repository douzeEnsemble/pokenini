<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\ElectionVote;
use App\Api\DTO\UpdatedTrainerPokemonElo;
use App\Api\Service\ElectionService;
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
        ElectionService $electionService,
    ): JsonResponse {
        $json = $request->getContent();

        if (!$json) {
            throw new BadRequestHttpException();
        }

        /** @var string[]|string[][] */
        $content = json_decode($json, true);

        try {
            $electionVote = new ElectionVote($content);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        /** @var UpdatedTrainerPokemonElo[] $results */
        $results = $electionService->update($electionVote);

        $winnerElo = 0;
        $losersElo = [];
        foreach ($results as $result) {
            $winnerElo = $result->getWinnerElo();

            $losersElo[$result->getLoserSlug()] = $result->getLoserElo();
        }

        // Better with serializer ?
        return new JsonResponse([
            'winnerFinalElo' => $winnerElo,
            'losersElo' => $losersElo,
        ]);
    }
}
