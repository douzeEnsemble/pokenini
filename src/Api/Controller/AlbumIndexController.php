<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\AlbumFilter\AlbumFilters;
use App\Api\DTO\AlbumFilter\AlbumFiltersRequest;
use App\Api\Service\Album\AlbumDexService;
use App\Api\Service\Album\AlbumPokemonService;
use App\Api\Service\Album\AlbumReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/album')]
class AlbumIndexController extends AbstractController
{
    #[Route(path: '/{trainerExternalId}/{dexSlug}', methods: ['GET'])]
    public function index(
        AlbumPokemonService $albumPokemonService,
        AlbumDexService $albumDexService,
        AlbumReportService $albumReportService,
        string $trainerExternalId,
        string $dexSlug,
        Request $request,
    ): JsonResponse {
        $albumsFilters = AlbumFiltersRequest::albumFiltersFromRequest($request);

        $pokemons = $albumPokemonService->get($trainerExternalId, $dexSlug, $albumsFilters);

        $report = $albumReportService->get($trainerExternalId, $dexSlug, AlbumFilters::createFromArray([]));
        $filteredReport = $albumReportService->get($trainerExternalId, $dexSlug, $albumsFilters);

        $dex = $albumDexService->get($trainerExternalId, $dexSlug);

        // Better with serializer ?
        return new JsonResponse([
            'dex' => $dex,
            'pokemons' => $pokemons,
            'report' => $report,
            'filteredReport' => $filteredReport,
        ]);
    }
}
