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
    public function testGet(
        string $listType,
        string $trainerExternalId,
        string $dexSlug,
        string $electionSlug,
        int $count,
    ): void {
        $electionList = $this
            ->getService($listType, $trainerExternalId, $dexSlug, $electionSlug, $count)
            ->get($trainerExternalId, $dexSlug, $electionSlug, $count)
        ;

        $this->assertSame($listType, $electionList->type);

        $pokemons = $electionList->items;
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
                'listType' => 'pick',
                'trainerExternalId' => '12',
                'dexSlug' => '123',
                'electionSlug' => '',
                'count' => 3,
            ],
            '123-5' => [
                'listType' => 'pick',
                'trainerExternalId' => '13',
                'dexSlug' => '123',
                'electionSlug' => 'a',
                'count' => 5,
            ],
            'all-12' => [
                'listType' => 'vote',
                'trainerExternalId' => '14',
                'dexSlug' => 'all',
                'electionSlug' => 'b',
                'count' => 12,
            ],
        ];
    }

    private function getService(
        string $listType,
        string $trainerExternalId,
        string $dexSlug,
        string $electionSlug,
        int $count,
    ): GetPokemonsService {
        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents(
            "/var/www/html/tests/resources/Web/unit/service/api/pokemons_to{$listType}_{$trainerExternalId}_{$dexSlug}_{$electionSlug}_{$count}.json"
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
                'https://api.domain/pokemons/to_choose',
                [
                    'query' => [
                        'trainer_external_id' => $trainerExternalId,
                        'dex_slug' => $dexSlug,
                        'election_slug' => $electionSlug,
                        'count' => $count,
                    ],
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
