<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\CollectionsController;
use App\Api\Service\CollectionsService;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(CollectionsController::class)]
#[CoversClass(CollectionsService::class)]
class CollectionsControllerTest extends AbstractTestControllerApi
{
    public function testGetCollection(): void
    {
        $this->apiRequest('GET', 'api/collections');

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(8, $content);

        $this->assertEquals([
            'name' => 'Sword, Shield - Dynamax Adventures bosses',
            'frenchName' => 'Sword, Shield - Boss des expéditions Dynamax',
            'slug' => 'swshdynamaxadventuresbosses',
        ], $content[0]);

        $this->assertEquals([
            'name' => "Scarlet, Violet - Terrarium's outbreaks",
            'frenchName' => 'Scarlet, Violet - Apparitions massives du Terrarium',
            'slug' => 'svmassoutbreaksterrarium',
        ], $content[3]);

        $this->assertEquals([
            'name' => 'Pokemon Go - Dynamax',
            'frenchName' => 'Pokemon Go - Dynamax',
            'slug' => 'pogodynamax',
        ], $content[7]);
    }

    public function testGetAuth(): void
    {
        $this->apiRequest('GET', 'api/types', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'douze']);

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(18, $content);
    }

    public function testGetBadAuth(): void
    {
        $this->apiRequest('GET', 'api/types', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'treize']);

        $this->assertEquals(401, $this->getResponse()->getStatusCode());
    }
}
