<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Service\CatchStatesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catch_states')]
class CatchStatesController extends AbstractController
{
    #[Route(path: '', methods: ['GET'])]
    public function get(
        CatchStatesService $service
    ): JsonResponse {
        $catchStates = $service->getAll();

        // Better with serializer ?
        return new JsonResponse($catchStates);
    }
}
