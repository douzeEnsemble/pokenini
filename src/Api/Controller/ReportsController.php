<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Service\PokedexService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reports')]
class ReportsController extends AbstractController
{
    public function __construct() {}

    #[Route(path: '', methods: ['GET'])]
    public function get(PokedexService $service): JsonResponse
    {
        return new JsonResponse([
            'catch_state_counts_defined_by_trainer' => $service->getCatchStateCountsDefinedByTrainer(),
            'dex_usage' => $service->getDexUsage(),
            'catch_state_usage' => $service->getCatchStateUsage(),
        ]);
    }
}
