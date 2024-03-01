<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Service\CategoryFormsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/forms/category')]
class CategoryFormsController extends AbstractController
{
    #[Route(path: '', methods: ['GET'])]
    public function get(
        CategoryFormsService $service
    ): JsonResponse {
        $forms = $service->getAll();

        // Better with serializer ?
        return new JsonResponse($forms);
    }
}
