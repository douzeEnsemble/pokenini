<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Service\SpecialFormsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forms/special')]
class SpecialFormsController extends AbstractController
{
    #[Route(path: '', methods: ['GET'])]
    public function get(
        SpecialFormsService $service
    ): JsonResponse {
        $forms = $service->getAll();

        // Better with serializer ?
        return new JsonResponse($forms);
    }
}
