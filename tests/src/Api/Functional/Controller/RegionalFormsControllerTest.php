<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\RegionalFormsController;
use App\Api\Service\RegionalFormsService;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(RegionalFormsController::class)]
#[CoversClass(RegionalFormsService::class)]
class RegionalFormsControllerTest extends AbstractTestControllerApi
{
    public function testGetCollection(): void
    {
        $this->apiRequest('GET', 'api/forms/regional');

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(3, $content);

        $this->assertEquals([
            'name' => 'Alolan',
            'frenchName' => "d'Alola",
            'slug' => 'alolan',
        ], $content[0]);

        $this->assertEquals([
            'name' => 'Hisuian',
            'frenchName' => 'de Hisui',
            'slug' => 'hisuian',
        ], $content[2]);
    }

    public function testGetAuth(): void
    {
        $this->apiRequest('GET', 'api/forms/regional', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'douze']);

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(3, $content);
    }

    public function testGetBadAuth(): void
    {
        $this->apiRequest('GET', 'api/forms/regional', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'treize']);

        $this->assertEquals(401, $this->getResponse()->getStatusCode());
    }
}
