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
                'avg_elo' => 12,
                'stddev_elo' => 12.45648,
                'count_elo' => 21,
            ])
        ;

        $service = new ElectionMetricsService($userTokenService, $apiService, 12);

        $metrics = $service->getMetrics('demo', 'whatever');

        $this->assertSame(12.0, $metrics->avg);
        $this->assertSame(12.45648, $metrics->stddev);
        $this->assertSame(21, $metrics->count);
    }
}
