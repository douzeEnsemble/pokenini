<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\DTO\ElectionVote;
use App\Web\Service\Api\GetLabelsService;
use App\Web\Service\ElectionMetricsService;
use App\Web\Service\ElectionTopService;
use App\Web\Service\ElectionVoteService;
use App\Web\Service\GetPokemonsListService;
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
        GetPokemonsListService $getPokemonsListService,
        GetLabelsService $getLabelsService,
        ElectionTopService $electionTopService,
        ElectionMetricsService $metricsService,
        GetTrainerPokedexService $getTrainerPokedexService,
        string $dexSlug,
        string $electionSlug = '',
    ): Response {
        $list = $getPokemonsListService->get($dexSlug, $electionSlug);
        $types = $getLabelsService->getTypes();
        $electionTop = $electionTopService->getTop($dexSlug, $electionSlug);

        $pokedex = $getTrainerPokedexService->getPokedexData($dexSlug, []);

        $metrics = $metricsService->getMetrics(
            $dexSlug,
            $electionSlug,
            count($pokedex['pokemons'])
        );

        $detachedCount = 0;
        foreach ($electionTop as $pokemon) {
            if ($pokemon['significance']) {
                ++$detachedCount;
            }
        }

        return $this->render(
            'Election/index.html.twig',
            [
                'listType' => $list->type,
                'pokemons' => $list->items,
                'pokedex' => $pokedex,
                'types' => $types,
                'electionTop' => $electionTop,
                'metrics' => $metrics,
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
        $data = $request->request->all();

        if (empty($data)) {
            throw new BadRequestHttpException('Data cannot be empty');
        }

        /** @var string[]|string[][] $data */
        $data = array_merge(
            [
                'dex_slug' => $dexSlug,
                'election_slug' => $electionSlug,
            ],
            $data
        );

        try {
            $electionVote = new ElectionVote($data);
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
