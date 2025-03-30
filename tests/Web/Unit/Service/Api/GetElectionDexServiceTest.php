<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Service\Api;

use App\Web\Service\Api\GetElectionDexService;
use App\Web\Service\Trait\CacheRegisterTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @internal
 */
#[CoversClass(GetElectionDexService::class)]
#[CoversClass(CacheRegisterTrait::class)]
class GetElectionDexServiceTest extends TestCase
{
    private ArrayAdapter $cache;

    public function testGet(): void
    {
        $expectedSlugs = [
            'homeshiny',
        ];

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($this->getService()->get()),
        );

        /** @var string $value */
        $value = $this->cache->getItem('election_dex')->get();

        /** @var string[][] */
        $jsonData = json_decode($value, true);

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($jsonData),
        );
    }

    public function testGetWithUnreleased(): void
    {
        $expectedSlugs = [
            'homeshiny',
            'redgreenblueyellowshiny',
        ];

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($this->getServiceWithUnreleased()->getWithUnreleased()),
        );

        /** @var string $value */
        $value = $this->cache->getItem('election_dex_include_unreleased_dex=1')->get();

        /** @var string[][] */
        $jsonData = json_decode($value, true);

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($jsonData),
        );

        $this->assertEquals(
            [
                'election_dex_include_unreleased_dex=1',
            ],
            $this->cache->getItem('register_dex')->get(),
        );
    }

    public function testGetWithPremium(): void
    {
        $expectedSlugs = [
            'home',
            'redgreenblueyellow',
        ];

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($this->getServiceWithPremium()->getWithPremium()),
        );

        /** @var string $value */
        $value = $this->cache->getItem('election_dex_include_premium_dex=1')->get();

        /** @var string[][] */
        $jsonData = json_decode($value, true);

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($jsonData),
        );

        $this->assertEquals(
            [
                'election_dex_include_premium_dex=1',
            ],
            $this->cache->getItem('register_dex')->get(),
        );
    }

    public function testGetWithUnreleasedAndPremium(): void
    {
        $expectedSlugs = [
            'home',
            'homeshiny',
            'redgreenblueyellow',
            'redgreenblueyellowshiny',
        ];

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($this->getServiceWithUnreleasedAndPremium()->getWithUnreleasedAndPremium()),
        );

        /** @var string $value */
        $value = $this->cache->getItem('election_dex_include_unreleased_dex=1&include_premium_dex=1')->get();

        /** @var string[][] */
        $jsonData = json_decode($value, true);

        $this->assertEquals(
            $expectedSlugs,
            self::extractSlugs($jsonData),
        );

        $this->assertEquals(
            [
                'election_dex_include_unreleased_dex=1&include_premium_dex=1',
            ],
            $this->cache->getItem('register_dex')->get(),
        );
    }

    private function getService(): GetElectionDexService
    {
        $json = (string) file_get_contents(
            '/var/www/html/tests/resources/Web/unit/service/api/election_dex.json'
        );

        return $this->getMockService(
            $json,
            'dex/can_hold_election',
        );
    }

    private function getServiceWithUnreleased(): GetElectionDexService
    {
        $json = (string) file_get_contents(
            '/var/www/html/tests/resources/Web/unit/service/api/election_dex_unreleased.json'
        );

        return $this->getMockService(
            $json,
            'dex/can_hold_election?include_unreleased_dex=1',
        );
    }

    private function getServiceWithPremium(): GetElectionDexService
    {
        $json = (string) file_get_contents(
            '/var/www/html/tests/resources/Web/unit/service/api/election_dex_premium.json'
        );

        return $this->getMockService(
            $json,
            'dex/can_hold_election?include_premium_dex=1',
        );
    }

    private function getServiceWithUnreleasedAndPremium(): GetElectionDexService
    {
        $json = (string) file_get_contents(
            '/var/www/html/tests/resources/Web/unit/service/api/election_dex_unreleased_and_premium.json'
        );

        return $this->getMockService(
            $json,
            'dex/can_hold_election?include_unreleased_dex=1&include_premium_dex=1',
        );
    }

    private function getMockService(
        string $json,
        string $endpoint,
    ): GetElectionDexService {
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
                'GET',
                "https://api.domain/{$endpoint}",
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

        return new GetElectionDexService(
            $logger,
            $client,
            'https://api.domain',
            $this->cache,
            'web',
            'douze',
        );
    }

    /**
     * @param string[][] $items
     *
     * @return string[]
     */
    private static function extractSlugs(array $items): array
    {
        $slugs = [];

        foreach ($items as $item) {
            $slugs[] = $item['slug'];
        }

        return $slugs;
    }
}
