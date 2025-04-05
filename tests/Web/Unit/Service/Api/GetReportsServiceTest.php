<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\GetReportsService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(GetReportsService::class)]
class GetReportsServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testGet(): void
    {
        $reports = $this->getService()->get();

        $this->assertArrayHasKey('catch_state_counts_defined_by_trainer', $reports);
        $this->assertCount(3, $reports['catch_state_counts_defined_by_trainer']);
        $this->assertArrayHasKey('dex_usage', $reports);
        $this->assertCount(12, $reports['dex_usage']);
        $this->assertArrayHasKey('catch_state_usage', $reports);
        $this->assertCount(6, $reports['catch_state_usage']);

        /** @var string $value */
        $value = $this->cache->getItem('reports')->get();
        $this->assertNotEmpty($value);
        $this->assertJson($value);
    }

    private function getService(): GetReportsService
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->exactly(2))
            ->method('info')
        ;

        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents('/var/www/html/tests/resources/Web/unit/service/api/reports.json');

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->exactly(2))
            ->method('getContent')
            ->willReturn($json)
        ;

        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://api.domain/reports',
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

        return new GetReportsService(
            $logger,
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
