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
                'whatever'
            )
            ->willReturn([
                'max_view' => 12,
                'max_view_count' => 48,
                'under_max_view_count' => 4,
                'elo_count' => 21,
            ])
        ;

        $service = new ElectionMetricsService($userTokenService, $apiService);

        $metrics = $service->getMetrics('demo', 'whatever');

        $this->assertSame(12, $metrics->maxView);
        $this->assertSame(48, $metrics->maxViewCount);
        $this->assertSame(4, $metrics->underMaxViewCount);
        $this->assertSame(21, $metrics->eloCount);
    }
}
