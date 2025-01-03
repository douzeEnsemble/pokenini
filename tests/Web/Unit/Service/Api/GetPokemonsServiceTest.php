<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\GetPokemonsService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(GetPokemonsService::class)]
class GetPokemonsServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    #[DataProvider('providerGet')]
    public function testGet(string $dexSlug, int $count): void
    {
        $pokemons = $this->getService($dexSlug, $count)->get($dexSlug, $count);

        $this->assertCount($count, $pokemons);

        $this->assertEmpty($this->cache->getValues());
    }

    /**
     * @return int[][]|string[][]
     */
    public static function providerGet(): array
    {
        return [
            '123-3' => [
                'dexSlug' => '123',
                'count' => 3,
            ],
            '123-5' => [
                'dexSlug' => '123',
                'count' => 5,
            ],
            'all-12' => [
                'dexSlug' => 'all',
                'count' => 12,
            ],
        ];
    }

    private function getService(string $dexSlug, int $count): GetPokemonsService
    {
        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents("/var/www/html/tests/resources/Web/unit/service/api/pokemons_list_{$dexSlug}_{$count}.json");

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
                "https://api.domain/pokemons/list/{$dexSlug}/{$count}",
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

        return new GetPokemonsService(
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
