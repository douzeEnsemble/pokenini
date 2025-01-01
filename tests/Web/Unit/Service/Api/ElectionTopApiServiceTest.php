<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\ElectionTopApiService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(ElectionTopApiService::class)]
class ElectionTopApiServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testGet(): void
    {
        $items = $this->getService('4564650', 'fav', 5)->getTop('4564650', 'fav', 5);

        $this->assertCount(5, $items);

        $this->assertEmpty($this->cache->getValues());
    }

    public function testGetBis(): void
    {
        $items = $this->getService('87654', 'pref', 10)->getTop('87654', 'pref', 10);

        $this->assertCount(10, $items);

        $this->assertEmpty($this->cache->getValues());
    }

    private function getService(
        string $trainerId,
        string $electionSlug,
        int $count,
    ): ElectionTopApiService {
        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents("/var/www/html/tests/resources/Web/unit/service/api/election_top_{$count}_{$trainerId}_{$electionSlug}.json");

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
                'https://api.domain/election/top',
                [
                    'headers' => [
                        'accept' => 'application/json',
                    ],
                    'auth_basic' => [
                        'web',
                        'douze',
                    ],
                    'body' => [
                        'trainer_external_id' => $trainerId,
                        'election_slug' => $electionSlug,
                        'count' => $count,
                    ],
                ],
            )
            ->willReturn($response)
        ;

        $this->cache = new ArrayAdapter();

        return new ElectionTopApiService(
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
