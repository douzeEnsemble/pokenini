<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Utils\JsonDecoder;

class ElectionTopApiService extends AbstractApiService
{
    /**
     * @return string[][]
     */
    public function getTop(
        string $trainerId,
        string $electionSlug,
        int $count,
    ): array {
        /** @var string $json */
        $json = $this->requestContent(
            'GET',
            '/election/top',
            [
                'body' => [
                    'trainer_external_id' => $trainerId,
                    'election_slug' => $electionSlug,
                    'count' => $count,
                ],
            ]
        );

        /** @var string[][] */
        return JsonDecoder::decode($json);
    }
}
