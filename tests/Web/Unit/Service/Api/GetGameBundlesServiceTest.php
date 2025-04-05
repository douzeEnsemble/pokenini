<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\GetGameBundlesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(GetGameBundlesService::class)]
class GetGameBundlesServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testGet(): void
    {
        $types = $this->getService()->get();

        $this->assertCount(20, $types);

        /** @var string $value */
        $value = $this->cache->getItem('game_bundles')->get();
        $this->assertNotEmpty($value);
        $this->assertJson($value);
    }

    private function getService(): GetGameBundlesService
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->exactly(2))
            ->method('info')
        ;

        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents('/var/www/html/tests/resources/Web/unit/service/api/game_bundles.json');

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
                'https://api.domain/game_bundles',
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

        return new GetGameBundlesService(
            $logger,
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
