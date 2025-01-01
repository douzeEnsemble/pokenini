<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

class ElectionVoteService extends AbstractApiService
{
    /**
     * @param string[] $losersSlugs
     */
    public function vote(
        string $trainerId,
        string $electionSlug,
        string $winnerSlug,
        array $losersSlugs,
    ): void {
        $this->request(
            'POST',
            '/favorite/vote',
            [
                'body' => [
                    'trainer_external_id' => $trainerId,
                    'election_slug' => $electionSlug,
                    'winner_slug' => $winnerSlug,
                    'losers_slugs' => $losersSlugs,
                ],
            ]
        );
    }
}
