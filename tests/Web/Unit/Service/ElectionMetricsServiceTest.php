<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service;

use App\Web\Security\UserTokenService;
use App\Web\Service\Api\ElectionMetricsApiService;
use App\Web\Service\ElectionMetricsService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ElectionMetricsService::class)]
class ElectionMetricsServiceTest extends TestCase
{
    public function testGetMetrics(): void
    {
        $userTokenService = $this->createMock(UserTokenService::class);
        $userTokenService
            ->expects($this->once())
            ->method('getLoggedUserToken')
            ->willReturn('8800088')
        ;

        $apiService = $this->createMock(ElectionMetricsApiService::class);
        $apiService
            ->expects($this->once())
            ->method('getMetrics')
            ->with(
                '8800088',
                'demo',
                'whatever',
                ['at' => ['poison', 'fire'], 'cf' => ['legendary']],
            )
            ->willReturn([
                'view_count_sum' => 12,
                'win_count_sum' => 48,
                'dex_total_count' => 48,
            ])
        ;

        $service = new ElectionMetricsService($userTokenService, $apiService, 12);

        $metrics = $service->getMetrics('demo', 'whatever', ['at' => ['poison', 'fire'], 'cf' => ['legendary']]);

        $this->assertSame(12, $metrics->viewCountSum);
        $this->assertSame(48, $metrics->winCountSum);
        $this->assertSame(1, $metrics->roundCount);
        $this->assertSame(48.0, $metrics->winnerAverage);
        $this->assertSame(4, $metrics->totalRoundCount);
    }
}
