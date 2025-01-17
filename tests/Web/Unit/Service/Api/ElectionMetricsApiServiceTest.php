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
        $items = $this
            ->getService(
                '4564650',
                'home',
                'fav',
            )
            ->getMetrics(
                '4564650',
                'home',
                'fav',
                [],
            )
        ;

        $this->assertSame(
            [
                'view_count_sum' => 6,
                'win_count_sum' => 2,
                'dex_total_count' => 48,
            ],
            $items
        );

        $this->assertEmpty($this->cache->getValues());
    }

    public function testGetBis(): void
    {
        $items = $this
            ->getService(
                '87654',
                'demo',
                'pref',
            )
            ->getMetrics(
                '87654',
                'demo',
                'pref',
                [],
            )
        ;

        $this->assertSame(
            [
                'view_count_sum' => 5,
                'win_count_sum' => 10,
                'dex_total_count' => 48,
            ],
            $items
        );

        $this->assertEmpty($this->cache->getValues());
    }

    public function testGetWithFilters(): void
    {
        $items = $this
            ->getService(
                '4564650',
                'home',
                'fav',
                '_cflegendary',
                ['cf' => ['legendary']],
            )
            ->getMetrics(
                '4564650',
                'home',
                'fav',
                ['cf' => ['legendary']],
            )
        ;

        $this->assertSame(
            [
                'view_count_sum' => 6,
                'win_count_sum' => 2,
                'dex_total_count' => 48,
            ],
            $items
        );

        $this->assertEmpty($this->cache->getValues());
    }

    public function testGetWithFiltersBis(): void
    {
        $items = $this
            ->getService(
                '87654',
                'demo',
                'pref',
                '_at0poison_at1fire_cfstarter',
                ['at' => ['poison', 'fire'], 'cf' => ['starter']],
            )
            ->getMetrics(
                '87654',
                'demo',
                'pref',
                ['at' => ['poison', 'fire'], 'cf' => ['starter']],
            )
        ;

        $this->assertSame(
            [
                'view_count_sum' => 5,
                'win_count_sum' => 10,
                'dex_total_count' => 48,
            ],
            $items
        );

        $this->assertEmpty($this->cache->getValues());
    }

    /**
     * @param string[]|string[][] $filters
     */
    private function getService(
        string $trainerId,
        string $dexSlug,
        string $electionSlug,
        string $filtersStr = '',
        array $filters = [],
    ): ElectionMetricsApiService {
        $client = $this->createMock(HttpClientInterface::class);

        $dir = '/var/www/html/tests/resources/Web/unit/service/api';
        $json = (string) file_get_contents(
            "{$dir}/election_metrics_{$trainerId}_{$dexSlug}_{$electionSlug}{$filtersStr}.json"
        );

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
                'https://api.domain/election/metrics',
                [
                    'query' => array_merge(
                        [
                            'trainer_external_id' => $trainerId,
                            'dex_slug' => $dexSlug,
                            'election_slug' => $electionSlug,
                        ],
                        $filters,
                    ),
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
