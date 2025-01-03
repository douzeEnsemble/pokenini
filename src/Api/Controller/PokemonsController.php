<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Repository\PokemonsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pokemons')]
class PokemonsController extends AbstractController
{
    #[Route(path: '/list/{dexSlug}/{count}', methods: ['GET'])]
    public function getNFromDex(
        string $dexSlug,
        int $count,
        PokemonsRepository $pokemonsRepository,
    ): JsonResponse {
        $list = $pokemonsRepository->getNFromDex($dexSlug, $count);

        // Better with serializer ?
        return new JsonResponse($list);
    }
}
