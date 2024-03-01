<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Repository\PokedexRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reports')]
class ReportsController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(path: '', methods: ['GET'])]
    public function get(PokedexRepository $repo): JsonResponse
    {
        return new JsonResponse([
            'catch_state_counts_defined_by_trainer' => $repo->getCatchStateCountsDefinedByTrainer(),
            'dex_usage' => $repo->getDexUsage(),
            'catch_state_usage' => $repo->getCatchStateUsage(),
        ]);
    }
}
