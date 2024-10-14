<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\AlbumFilters\FromRequest;
use App\Web\AlbumFilters\Mapping;
use App\Web\Security\User;
use App\Web\Service\Api\GetLabelsService;
use App\Web\Service\GetTrainerPokedexService;
use App\Web\Service\TrainerIdsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/album')]
class AlbumIndexController extends AbstractController
{
    public function __construct(
        private readonly TrainerIdsService $trainerIdsService,
        private readonly GetTrainerPokedexService $getTrainerPokedexService,
        private readonly GetLabelsService $getLabelsService,
    ) {}

    #[Route(
        '/{dexSlug}',
        requirements: [
            'dexSlug' => '[A-Za-z0-9]+(?:-[A-Za-z0-9]+)*',
        ],
        methods: ['GET']
    )]
    public function index(
        Request $request,
        string $dexSlug,
    ): Response {
        $this->trainerIdsService->init();

        $trainerId = $this->trainerIdsService->getTrainerId();

        if (!$trainerId) {
            throw $this->createNotFoundException();
        }

        $filters = FromRequest::get($request);
        $apiFilters = Mapping::get($filters);

        $pokedex = $this->getTrainerPokedexService->getPokedexDataByTrainerId($dexSlug, $apiFilters, $trainerId);
        if (null === $pokedex || empty($pokedex['dex'])) {
            throw $this->createNotFoundException();
        }

        $dex = $pokedex['dex'];
        $this->dexIsGranted($dex);

        $catchStates = $this->getLabelsService->getCatchStates();
        $types = $this->getLabelsService->getTypes();
        $categoryForms = $this->getLabelsService->getFormsCategory();
        $regionalForms = $this->getLabelsService->getFormsRegional();
        $specialForms = $this->getLabelsService->getFormsSpecial();
        $variantForms = $this->getLabelsService->getFormsVariant();
        $variantForms = $this->getLabelsService->getFormsVariant();
        $gameBundles = $this->getLabelsService->getGameBundles();
        $collections = $this->getLabelsService->getCollections();

        $pokemons = $pokedex['pokemons'];

        $loggedTrainerId = $this->trainerIdsService->getLoggedTrainerId();

        return $this->render('Album/index.html.twig', [
            'currentDexSlug' => $dexSlug,
            'dex' => $dex,
            'report' => $pokedex['report'],
            'list' => $pokemons,
            'catchStates' => $catchStates,
            'types' => $types,
            'categoryForms' => $categoryForms,
            'regionalForms' => $regionalForms,
            'specialForms' => $specialForms,
            'variantForms' => $variantForms,
            'gameBundles' => $gameBundles,
            'collections' => $collections,
            'mode' => 'read',
            'filters' => $filters,
            'trainerId' => $trainerId,
            'loggedTrainerId' => $loggedTrainerId,
            'requestedTrainerId' => $this->trainerIdsService->getRequestedTrainerId(),
            'allowedToEdit' => $trainerId === $loggedTrainerId,
        ]);
    }

    /**
     * @param string[]|string[][] $dex
     */
    private function dexIsGranted(array $dex): void
    {
        if ($dex['is_private']
            && $this->trainerIdsService->getTrainerId() != $this->trainerIdsService->getLoggedTrainerId()
        ) {
            throw $this->createNotFoundException();
        }

        /** @var User $user */
        $user = $this->getUser();

        if (!$dex['is_released'] && !$user->isAnAdmin()) {
            throw $this->createNotFoundException();
        }
    }
}
