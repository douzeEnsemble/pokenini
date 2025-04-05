<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\AdminActionService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(AdminActionService::class)]
class AdminActionServiceTest extends TestCase
{
    private TagAwareAdapter $cache;

    public function testUpdate(): void
    {
        $json = <<<'JSON'
            {
                "suffix": "update/start"
            }
            JSON;

        $this->assertEquals(
            $json,
            $this->getService('update/start')->update('start')
        );

        $this->assertEmpty($this->cache->getItems());
    }

    public function testCalculate(): void
    {
        $json = <<<'JSON'
            {
                "suffix": "calculate/start"
            }
            JSON;

        $this->assertEquals(
            $json,
            $this->getService('calculate/start')->calculate('start')
        );

        $this->assertEmpty($this->cache->getItems());
    }

    private function getService(string $suffix): AdminActionService
    {
        $json = <<<JSON
            {
                "suffix": "{$suffix}"
            }
            JSON;

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->exactly(2))
            ->method('info')
        ;

        $client = $this->createMock(HttpClientInterface::class);

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
                'POST',
                "https://api.domain/istration/{$suffix}",
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

        $this->cache = new TagAwareAdapter(new ArrayAdapter(), new ArrayAdapter());

        return new AdminActionService(
            $logger,
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
