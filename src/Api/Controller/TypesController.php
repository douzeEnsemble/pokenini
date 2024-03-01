<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Service\TypesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/types')]
class TypesController extends AbstractController
{
    #[Route(path: '', methods: ['GET'])]
    public function get(
        TypesService $service
    ): JsonResponse {
        $types = $service->getAll();

        // Better with serializer ?
        return new JsonResponse($types);
    }
}
