<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\DTO\ElectionVote;

class ElectionVoteApiService extends AbstractApiService
{
    public function vote(
        string $trainerId,
        ElectionVote $electionVote,
    ): void {
        $this->request(
            'POST',
            '/election/vote',
            [
                'body' => [
                    'trainer_external_id' => $trainerId,
                    'election_slug' => $electionVote->electionSlug,
                    'winner_slug' => $electionVote->winnerSlug,
                    'losers_slugs' => $electionVote->losersSlugs,
                ],
            ]
        );
    }
}
