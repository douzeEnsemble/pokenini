<?php

namespace App\Web\Service;

use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetPokemonsService;

class GetPokemonsListService
{
    public function __construct(
        private readonly UserTokenService $userTokenService,
        private readonly GetPokemonsService $getPokemonsService,
    ) {}

    /**
     * @return string[][]
     */
    public function get(string $dexSlug, string $electionSlug, int $count): ?array
    {
        $trainerId = $this->userTokenService->getLoggedUserToken();

        return $this->getPokemonsService->get(
            $trainerId,
            $dexSlug,
            $electionSlug,
            $count,
        );
    }
}
