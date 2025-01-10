<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\DTO\ElectionVote;
use App\Web\Service\Api\GetLabelsService;
use App\Web\Service\Api\GetPokemonsService;
use App\Web\Service\ElectionMetricsService;
use App\Web\Service\ElectionTopService;
use App\Web\Service\ElectionVoteService;
use App\Web\Service\GetTrainerPokedexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/election')]
class ElectionController extends AbstractController
{
    #[Route(
        '/{dexSlug}/{electionSlug}',
        requirements: [
            'dexSlug' => '[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*',
            'electionSlug' => '[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*',
        ],
        methods: ['GET']
    )]
    public function index(
        GetPokemonsService $getPokemonsService,
        GetLabelsService $getLabelsService,
        ElectionTopService $electionTopService,
        ElectionMetricsService $electionMetricsService,
        GetTrainerPokedexService $getTrainerPokedexService,
        int $electionCandidateCount,
        string $dexSlug,
        string $electionSlug = '',
    ): Response {
        $pokemons = $getPokemonsService->get($dexSlug, $electionCandidateCount);
        $types = $getLabelsService->getTypes();
        $electionTop = $electionTopService->getTop($dexSlug, $electionSlug);
        $electionMetrics = $electionMetricsService->getMetrics($dexSlug, $electionSlug);
        $pokedex = $getTrainerPokedexService->getPokedexData($dexSlug, []);

        $detachedCount = 0;
        foreach ($electionTop as $pokemon) {
            if ($pokemon['significance']) {
                ++$detachedCount;
            }
        }

        return $this->render(
            'Election/index.html.twig',
            [
                'pokemons' => $pokemons,
                'pokedex' => $pokedex,
                'types' => $types,
                'electionTop' => $electionTop,
                'electionMetrics' => $electionMetrics,
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
        $json = $request->getContent();

        if (empty($json)) {
            throw new BadRequestHttpException('Content cannot be empty');
        }

        $content = json_decode($json, true);

        if (!is_array($content)) {
            throw new BadRequestHttpException('Content must be a JSON array');
        }

        /** @var string[]|string[][] $content */
        $content = array_merge(
            [
                'dex_slug' => $dexSlug,
                'election_slug' => $electionSlug,
            ],
            $content
        );

        try {
            $electionVote = new ElectionVote($content);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $electionVoteService->vote($electionVote);

        return $this->redirectToRoute(
            'app_web_election_index',
            [
                'dexSlug' => $electionVote->dexSlug,
                'electionSlug' => $electionVote->electionSlug,
            ]
        );
    }
}
