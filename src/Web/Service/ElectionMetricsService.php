<?php

namespace App\Web\Service;

use App\Web\DTO\ElectionMetrics;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ElectionMetricsApiService;

class ElectionMetricsService
{
    public function __construct(
        private readonly UserTokenService $userTokenService,
        private readonly ElectionMetricsApiService $apiService,
        private readonly int $electionCandidateCount,
    ) {}

    /**
     * @param string[]|string[][] $filters
     */
    public function getMetrics(string $dexSlug, string $electionSlug, array $filters): ElectionMetrics
    {
        $trainerId = $this->userTokenService->getLoggedUserToken();

        /** @var float[]|int[] */
        $data = $this->apiService->getMetrics($trainerId, $dexSlug, $electionSlug, $filters);

        return new ElectionMetrics($data, $this->electionCandidateCount);
    }
}
