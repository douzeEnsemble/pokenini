<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\DTO\ElectionVote;
use App\Web\Service\Api\GetLabelsService;
use App\Web\Service\Api\GetPokemonsService;
use App\Web\Service\ElectionTopService;
use App\Web\Service\ElectionVoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/election')]
class ElectionController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(
        Request $request,
        GetPokemonsService $getPokemonsService,
        GetLabelsService $getLabelsService,
        ElectionTopService $electionTopService,
    ): Response {
        $pokemons = $getPokemonsService->get(3);
        $types = $getLabelsService->getTypes();
        $electionTop = $electionTopService->getTop($request->query->getAlnum('election_slug', ''));

        return $this->render(
            'Election/index.html.twig',
            [
                'pokemons' => $pokemons,
                'types' => $types,
                'electionTop' => $electionTop,
            ]
        );
    }

    #[Route('', methods: ['POST'])]
    public function vote(
        Request $request,
        ElectionVoteService $electionVoteService,
    ): Response {
        $content = $request->request->all();

        if (!$content) {
            throw new BadRequestHttpException();
        }

        try {
            $electionVote = new ElectionVote($content);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $electionVoteService->vote($electionVote);

        return $this->redirectToRoute('app_web_election_index');
    }
}
