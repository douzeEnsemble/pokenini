<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\Exception\NoLoggedUserException;
use App\Web\Security\UserTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('')]
    public function index(
        UserTokenService $userTokenService,
    ): Response {
        try {
            $connectedUserId = $userTokenService->getLoggedUserToken();
        } catch (NoLoggedUserException $e) {
            $connectedUserId = null;
        }

        return $this->render(
            'Home/index.html.twig',
            [
                'connectedUserId' => $connectedUserId,
            ]
        );
    }
}
