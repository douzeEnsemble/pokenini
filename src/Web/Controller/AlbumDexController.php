<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\AlbumFilters\FromRequest;
use App\Web\AlbumFilters\Mapping;
use App\Web\Exception\NoLoggedUserException;
use App\Web\Security\User;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetDexService;
use App\Web\Service\Api\GetLabelsService;
use App\Web\Service\GetTrainerPokedexService;
use App\Web\Service\TrainerIdsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/album')]
class AlbumDexController extends AbstractController
{
    public function __construct(
        private readonly TrainerIdsService $trainerIdsService,
        private readonly GetTrainerPokedexService $getTrainerPokedexService,
        private readonly GetLabelsService $getLabelsService,
    ) {}

    #[Route('/dex', methods: ['GET'])]
    public function index(
        GetDexService $getDexService,
        UserTokenService $userTokenService,
        string $demoUserId,
    ): Response {
        $connectedUserId = null;

        try {
            $userId = $connectedUserId = $userTokenService->getLoggedUserToken();
        } catch (NoLoggedUserException $e) {
            $userId = $demoUserId;
        }

        $dex = $this->isGranted('ROLE_ADMIN')
            ? $getDexService->getWithUnreleasedAndPremium($userId)
            : $getDexService->getWithPremium($userId);

        return $this->render(
            'Album/dex.html.twig',
            [
                'dex' => $dex,
                'userId' => $userId,
                'connectedUserId' => $connectedUserId,
            ]
        );
    }
}
