<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\DTO\TrainerPokemonEloListQueryOptions;
use App\Api\Service\GetNPokemonsToChooseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/pokemons')]
class PokemonsController extends AbstractController
{
    #[Route(path: '/to_choose', methods: ['GET'])]
    public function getNPokemonsToChoose(
        Request $request,
        GetNPokemonsToChooseService $getNPokemonsToChooseService,
        SerializerInterface $serializer,
    ): Response {
        $queryOptions = new TrainerPokemonEloListQueryOptions($request->query->all());

        $list = $getNPokemonsToChooseService->getNPokemonsToChoose($queryOptions);

        return new Response($serializer->serialize(
            $list,
            'json'
        ));
    }
}
