<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller\Debug;

use App\Api\Controller\Debug\DebugDexController;
use App\Api\Service\DexAvailabilitiesService;
use App\Tests\Api\Functional\Controller\AbstractTestControllerApi;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(DebugDexController::class)]
#[CoversClass(DexAvailabilitiesService::class)]
class DebugDexControllerTest extends AbstractTestControllerApi
{
    public function testDex(): void
    {
        $this->apiRequest('GET', 'api/debogage/dex/redgreenblueyellow');

        $this->assertResponseIsOK();

        $content = $this->getResponseContent();

        $this->assertStringNotContainsString('__', $content);

        $this->assertJson($content);

        /** @var null|string[][]|string[][][] $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertNotNull($data);

        $this->assertArrayHasKey('identifier', $data);
        $this->assertEquals('redgreenblueyellow', $data['slug']);

        $this->assertArrayHasKey('region', $data);
        $this->assertArrayHasKey('identifier', $data['region']);
        $this->assertEquals('kanto', $data['region']['slug']);
    }

    public function testDexNotFound(): void
    {
        $this->apiRequest('GET', 'api/debogage/dex/homeshinyapriballs');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testDexAvailabilities(): void
    {
        $this->apiRequest('GET', 'api/debogage/dex/redgreenblueyellow/availabilities');

        $this->assertResponseIsOK();

        $content = $this->getResponseContent();

        $this->assertStringNotContainsString('__', $content);

        $this->assertJson($content);

        /** @var null|string[] $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertNotNull($data);

        $this->assertContains('bulbasaur', $data);
        $this->assertContains('douze', $data);
    }

    public function testDexAvailabilitiesNotFound(): void
    {
        $this->apiRequest('GET', 'api/debogage/dex/homeshinyapriballs/availabilities');

        $this->assertResponseStatusCodeSame(404);
    }
}
