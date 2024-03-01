<?php

declare(strict_types=1);

namespace App\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/outerroom')]
class OuterRoomController extends AbstractController
{
    #[Route('')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_TRAINER') || $this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_web_home_index');
        }

        return $this->render(
            'OuterRoom/index.html.twig'
        );
    }
}
