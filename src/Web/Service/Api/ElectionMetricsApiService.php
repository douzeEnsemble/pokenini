<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\Utils\JsonDecoder;

class ElectionMetricsApiService extends AbstractApiService
{
    /**
     * @param string[]|string[][] $filters
     *
     * @return float[]|int[]
     */
    public function getMetrics(
        string $trainerId,
        string $dexSlug,
        string $electionSlug,
        array $filters,
    ): array {
        /** @var string $json */
        $json = $this->requestContent(
            'GET',
            '/election/metrics',
            [
                'query' => array_merge(
                    [
                        'trainer_external_id' => $trainerId,
                        'dex_slug' => $dexSlug,
                        'election_slug' => $electionSlug,
                    ],
                    $filters,
                ),
            ],
        );

        /** @var float[]|int[] */
        return JsonDecoder::decode($json);
    }
}
