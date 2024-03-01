<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\AlbumFilters\FromRequest;
use App\Web\AlbumFilters\Mapping;
use App\Web\Exception\NoLoggedUserException;
use App\Web\Security\User;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetLabelsService;
use App\Web\Service\Api\GetPokedexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route('/album')]
class AlbumIndexController extends AbstractController
{
    use ValidatorJsonResponseTrait;

    public function __construct(
        private readonly UserTokenService $userTokenService,
        private readonly ValidatorInterface $validator,
        private readonly GetPokedexService $getPokedexService,
        private readonly GetLabelsService $getLabelsService,
    ) {
    }

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
        $loggedTrainerId = null;
        $queryTrainerId = $request->query->getAlnum('t');

        try {
            $loggedTrainerId = $this->userTokenService->getLoggedUserToken();

            $trainerId = !empty($queryTrainerId) ? $queryTrainerId : $loggedTrainerId;
        } catch (NoLoggedUserException $e) {
            $trainerId = $queryTrainerId;
        }

        if (empty($trainerId)) {
            throw $this->createNotFoundException();
        }

        $filters = FromRequest::get($request);
        $apiFilters = Mapping::get($filters);

        try {
            $pokedex = $this->getPokedexService->get($dexSlug, $trainerId, $apiFilters);
        } catch (HttpExceptionInterface | TransportExceptionInterface $e) {
            throw $this->createNotFoundException();
        }

        $dex = $pokedex['dex'];

        if ($dex['is_private'] && $trainerId != $loggedTrainerId) {
            throw $this->createNotFoundException();
        }

        /** @var User $user */
        $user = $this->getUser();

        if (!$dex['is_released'] && !$user->isAnAdmin()) {
            throw $this->createNotFoundException();
        }

        $catchStates = $this->getLabelsService->getCatchStates();
        $types = $this->getLabelsService->getTypes();
        $categoryForms = $this->getLabelsService->getFormsCategory();
        $regionalForms = $this->getLabelsService->getFormsRegional();
        $specialForms = $this->getLabelsService->getFormsSpecial();
        $variantForms = $this->getLabelsService->getFormsVariant();

        $pokemons = $pokedex['pokemons'];

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
            'mode' => 'read',
            'filters' => $filters,
            'trainerId' => $trainerId,
            'loggedTrainerId' => $loggedTrainerId,
            'queryTrainerId' => $queryTrainerId,
            'allowedToEdit' => $trainerId === $loggedTrainerId,
        ]);
    }
}
