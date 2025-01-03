<?php

declare(strict_types=1);

namespace App\Api\Service;

use App\Api\DTO\ElectionVote;
use App\Api\Repository\TrainerVoteRepository;

class ElectionRegisterVoteService
{
    public function __construct(
        private readonly TrainerVoteRepository $repository,
    ) {}

    public function register(ElectionVote $electionVote): int
    {
        $this->repository->register(
            $electionVote->trainerExternalId,
            $electionVote->electionSlug,
            $electionVote->winnersSlugs,
            $electionVote->losersSlugs,
        );

        return $this->repository->getCount(
            $electionVote->trainerExternalId,
            $electionVote->electionSlug,
        );
    }
}
