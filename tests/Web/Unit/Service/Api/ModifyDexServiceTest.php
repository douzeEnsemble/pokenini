<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\ModifyDexService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @internal
 */
#[CoversClass(ModifyDexService::class)]
class ModifyDexServiceTest extends TestCase
{
    private TagAwareAdapter $cache;

    public function testModify(): void
    {
        $this
            ->getService(
                'dex/123/home',
                'data-whatever',
            )
            ->modify(
                'home',
                'data-whatever',
                '123',
            )
        ;

        $this->assertEmpty($this->cache->getItems());
    }

    private function getService(
        string $suffix,
        string $body
    ): ModifyDexService {
        $logger = $this->createMock(LoggerInterface::class);

        $client = $this->createMock(HttpClientInterface::class);

        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                'PUT',
                "https://api.domain/{$suffix}",
                [
                    'headers' => [
                        'accept' => 'application/json',
                    ],
                    'auth_basic' => [
                        'web',
                        'douze',
                    ],
                    'body' => $body,
                ],
            )
        ;

        $this->cache = new TagAwareAdapter(new ArrayAdapter(), new ArrayAdapter());

        return new ModifyDexService(
            $logger,
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
