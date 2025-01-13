<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\ElectionMetricsApiService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(ElectionMetricsApiService::class)]
class ElectionMetricsApiServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testGet(): void
    {
        $items = $this->getService('4564650', 'home', 'fav')->getMetrics('4564650', 'home', 'fav');

        $this->assertSame(
            [
                'max_view' => 1,
                'max_view_count' => 3,
                'under_max_view_count' => 3,
                'elo_count' => 6,
            ],
            $items
        );

        $this->assertEmpty($this->cache->getValues());
    }

    public function testGetBis(): void
    {
        $items = $this->getService('87654', 'demo', 'pref')->getMetrics('87654', 'demo', 'pref');

        $this->assertSame(
            [
                'max_view' => 12,
                'max_view_count' => 2,
                'under_max_view_count' => 0,
                'elo_count' => 6,
            ],
            $items
        );

        $this->assertEmpty($this->cache->getValues());
    }

    private function getService(
        string $trainerId,
        string $dexSlug,
        string $electionSlug,
    ): ElectionMetricsApiService {
        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents("/var/www/html/tests/resources/Web/unit/service/api/election_metrics_{$trainerId}_{$dexSlug}_{$electionSlug}.json");

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getContent')
            ->willReturn($json)
        ;

        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                "https://api.domain/election/metrics?trainer_external_id={$trainerId}&dex_slug={$dexSlug}&election_slug={$electionSlug}",
                [
                    'headers' => [
                        'accept' => 'application/json',
                    ],
                    'auth_basic' => [
                        'web',
                        'douze',
                    ],
                ],
            )
            ->willReturn($response)
        ;

        $this->cache = new ArrayAdapter();

        return new ElectionMetricsApiService(
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
