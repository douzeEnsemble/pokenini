<?php

namespace App\Web\Service;

use App\Web\DTO\ElectionVote;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ElectionVoteApiService;

class ElectionVoteService
{
    public function __construct(
        private readonly UserTokenService $userTokenService,
        private readonly ElectionVoteApiService $apiService,
    ) {}

    public function vote(ElectionVote $electionVote): void
    {
        $trainerId = $this->userTokenService->getLoggedUserToken();

        $this->apiService->vote($trainerId, $electionVote);
    }
}
