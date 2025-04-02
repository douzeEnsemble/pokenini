<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\ModifyAlbumService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @internal
 */
#[CoversClass(ModifyAlbumService::class)]
class ModifyAlbumServiceTest extends TestCase
{
    private TagAwareAdapter $cache;

    public function testModifyPatch(): void
    {
        $this
            ->getService(
                'PATCH',
                'album/123/home/pikachu',
                'yes',
            )
            ->modify(
                'PATCH',
                'home',
                'pikachu',
                'yes',
                '123',
            )
        ;

        $this->assertEmpty($this->cache->getItems());
    }

    public function testModifyPut(): void
    {
        $this
            ->getService(
                'PUT',
                'album/123/home/pikachu',
                'yes',
            )
            ->modify(
                'PUT',
                'home',
                'pikachu',
                'yes',
                '123',
            )
        ;

        $this->assertEmpty($this->cache->getItems());
    }

    public function testModifyPost(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $logger = $this->createMock(LoggerInterface::class);

        $client = $this->createMock(HttpClientInterface::class);

        $service = new ModifyAlbumService(
            $logger,
            $client,
            'https://api.domain',
            new TagAwareAdapter(new ArrayAdapter(), new ArrayAdapter()),
            'web',
            'douze',
        );

        $service->modify(
            'POST',
            'home',
            'pikachu',
            'yes',
            '123',
        );
    }

    private function getService(
        string $method,
        string $suffix,
        string $body
    ): ModifyAlbumService {
        $logger = $this->createMock(LoggerInterface::class);

        $client = $this->createMock(HttpClientInterface::class);

        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                $method,
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

        return new ModifyAlbumService(
            $logger,
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
