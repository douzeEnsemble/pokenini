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
    #[Route(path: '/list/{count}', methods: ['GET'])]
    public function getN(
        int $count,
        PokemonsRepository $pokemonsRepository,
    ): JsonResponse {
        $list = $pokemonsRepository->getN($count);

        // Better with serializer ?
        return new JsonResponse($list);
    }
}
