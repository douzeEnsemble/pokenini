<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Service\CollectionsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/collections')]
class CollectionsController extends AbstractController
{
    #[Route(path: '', methods: ['GET'])]
    public function get(
        CollectionsService $service
    ): JsonResponse {
        $types = $service->getAll();

        // Better with serializer ?
        return new JsonResponse($types);
    }
}
