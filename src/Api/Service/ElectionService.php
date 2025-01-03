<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\ElectionVote;
use App\Api\DTO\ElectionVoteResult;

class ElectionService
{
    public function __construct(
        private readonly ElectionUpdateEloService $updateEloService,
        private readonly ElectionRegisterVoteService $registerVoteService,
    ) {}

    public function vote(ElectionVote $electionVote): ElectionVoteResult
    {
        $pokemonsElo = $this->updateEloService->update($electionVote);
        $voteCount = $this->registerVoteService->register($electionVote);

        return new ElectionVoteResult(
            $electionVote,
            $pokemonsElo,
            $voteCount,
        );
    }
}
