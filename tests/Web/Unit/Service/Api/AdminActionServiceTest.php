<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\AdminActionService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 *
 * @coversNothing
 */
class AdminActionServiceTest extends TestCase
{
    private ArrayAdapter $cache;

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

        $this->assertEmpty($this->cache->getValues());
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

        $this->assertEmpty($this->cache->getValues());
    }

    private function getService(string $suffix): AdminActionService
    {
        $client = $this->createMock(HttpClientInterface::class);

        $json = <<<JSON
            {
                "suffix": "{$suffix}"
            }
            JSON;

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

        $this->cache = new ArrayAdapter();

        return new AdminActionService(
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
