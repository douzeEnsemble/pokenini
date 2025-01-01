<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\DTO\ElectionVote;
use App\Web\Service\ElectionVoteService;
use App\Web\Service\Api\GetPokemonsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/election')]
class ElectionController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[IsGranted('ROLE_TRAINER')]
    public function index(
        GetPokemonsService $getPokemonsService,
    ): Response {
        $pokemons = $getPokemonsService->get(3);

        return $this->render(
            'Election/index.html.twig',
            [
                'pokemons' => $pokemons,
            ]
        );
    }

    #[Route('', methods: ['POST'])]
    #[IsGranted('ROLE_TRAINER')]
    public function vote(
        Request $request,
        ElectionVoteService $electionVoteService,
    ): Response {
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

        $electionVoteService->vote($electionVote);

        return new JsonResponse();
    }
}
