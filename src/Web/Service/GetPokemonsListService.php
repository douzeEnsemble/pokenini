<?php

namespace App\Web\Service;

use App\Web\DTO\ElectionPokemonsList;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetPokemonsService;

class GetPokemonsListService
{
    public function __construct(
        private readonly UserTokenService $userTokenService,
        private readonly GetPokemonsService $getPokemonsService,
        private readonly int $electionCandidateCount,
    ) {}

    public function get(string $dexSlug, string $electionSlug): ElectionPokemonsList
    {
        $trainerId = $this->userTokenService->getLoggedUserToken();

        return $this->getPokemonsService->get(
            $trainerId,
            $dexSlug,
            $electionSlug,
            $this->electionCandidateCount,
        );
    }
}
