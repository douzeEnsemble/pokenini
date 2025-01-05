<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\DTO\ElectionVote;
use App\Web\DTO\ElectionVoteResult;
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
    public const SESSION_VOTE_RESULT_NAME = 'vote_result';

    #[Route(
        '/{dexSlug}/{electionSlug}',
        requirements: [
            'dexSlug' => '[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*',
            'electionSlug' => '[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*',
        ],
        methods: ['GET']
    )]
    public function index(
        Request $request,
        GetPokemonsService $getPokemonsService,
        GetLabelsService $getLabelsService,
        ElectionTopService $electionTopService,
        int $electionCandidateCount,
        string $dexSlug,
        string $electionSlug = '',
    ): Response {
        $pokemons = $getPokemonsService->get($dexSlug, $electionCandidateCount);
        $types = $getLabelsService->getTypes();
        $electionTop = $electionTopService->getTop($dexSlug, $electionSlug);

        $detachedCount = 0;
        
        $session = $request->getSession();

        /** @var ElectionVoteResult $result */
        $result = $session->get(self::SESSION_VOTE_RESULT_NAME);

        return $this->render(
            'Election/index.html.twig',
            [
                'pokemons' => $pokemons,
                'types' => $types,
                'electionTop' => $electionTop,
                'result' => $result,
                'detachedCount' => $detachedCount,
            ]
        );
    }

    #[Route(
        '/{dexSlug}/{electionSlug}',
        requirements: [
            'dexSlug' => '[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*',
            'electionSlug' => '[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*',
        ],
        methods: ['POST']
    )]
    public function vote(
        Request $request,
        ElectionVoteService $electionVoteService,
        string $dexSlug,
        string $electionSlug = '',
    ): Response {
        $content = $request->request->all();

        if (!$content) {
            throw new BadRequestHttpException();
        }

        $content = array_merge(
            [
                'dex_slug' => $dexSlug,
                'election_slug' => $electionSlug,
            ],
            $content
        );

        dump($content);

        try {
            $electionVote = new ElectionVote($content);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $result = $electionVoteService->vote($electionVote);

        $session = $request->getSession();
        $session->set(self::SESSION_VOTE_RESULT_NAME, $result);

        return $this->redirectToRoute(
            'app_web_election_index',
            [
                'dexSlug' => $dexSlug,
            ]
        );
    }
}
