<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\GetCatchStatesService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(GetCatchStatesService::class)]
class GetCatchStatesServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testGet(): void
    {
        $expectedResult = [
            [
                'name' => 'No',
                'frenchName' => 'Non',
                'slug' => 'no',
                'color' => '#e57373',
            ],
            [
                'name' => 'To evolve',
                'frenchName' => 'af. évoluer',
                'slug' => 'toevolve',
                'color' => '#9575cd',
            ],
            [
                'name' => 'To breed',
                'frenchName' => 'af. reproduire',
                'slug' => 'tobreed',
                'color' => '#4fc3f7',
            ],
            [
                'name' => 'To transfer',
                'frenchName' => 'à transférer',
                'slug' => 'totransfer',
                'color' => '#ffd54f',
            ],
            [
                'name' => 'To trade',
                'frenchName' => 'À échanger',
                'slug' => 'totrade',
                'color' => '#ff9100',
            ],
            [
                'name' => 'Yes',
                'frenchName' => 'Oui',
                'slug' => 'yes',
                'color' => '#66bb6a',
            ],
        ];

        $this->assertEquals(
            $expectedResult,
            $this->getService()->get(),
        );

        /** @var string $value */
        $value = $this->cache->getItem('catch_states')->get();

        $this->assertJsonStringEqualsJsonString(
            (string) json_encode($expectedResult),
            $value,
        );
    }

    private function getService(): GetCatchStatesService
    {
        $client = $this->createMock(HttpClientInterface::class);

        $json = (string) file_get_contents('/var/www/html/tests/resources/Web/unit/service/api/catch_states.json');

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
                'https://api.domain/catch_states',
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

        return new GetCatchStatesService(
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }
}
