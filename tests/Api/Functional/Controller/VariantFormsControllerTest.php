<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

class VariantFormsControllerTest extends AbstractTestControllerApi
{
    public function testGetCollection(): void
    {
        $this->apiRequest('GET', 'api/forms/variant');

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(7, $content);

        $this->assertEquals([
            'name' => 'Gender',
            'frenchName' => 'Sexe',
            'slug' => 'gender',
        ], $content[0]);

        $this->assertEquals([
            'name' => 'Baby',
            'frenchName' => 'Bébé',
            'slug' => 'baby',
        ], $content[2]);

        $this->assertEquals([
            'name' => 'Unobtainable',
            'frenchName' => 'Non obtenable',
            'slug' => 'unobtainable',
        ], $content[6]);
    }

    public function testGetAuth(): void
    {
        $this->apiRequest('GET', 'api/forms/variant', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'douze']);

        $this->assertResponseIsOK();

        /** @var string[] $content */
        $content = $this->getJsonDecodedResponseContent();

        $this->assertCount(7, $content);
    }

    public function testGetBadAuth(): void
    {
        $this->apiRequest('GET', 'api/forms/variant', [], ['PHP_AUTH_USER' => 'web', 'PHP_AUTH_PW' => 'treize']);

        $this->assertEquals(401, $this->getResponse()->getStatusCode());
    }
}
