<?php

declare(strict_types=1);

namespace App\Tests\Web\Functional\Album\Action;

use App\Api\Service\TrainerDexService;
use App\Web\Controller\AlbumUpsertController;
use App\Web\Security\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(AlbumUpsertController::class)]
#[CoversClass(TrainerDexService::class)]
class UpdateTest extends WebTestCase
{
    public function testUpdateConnected(): void
    {
        $client = static::createClient();

        $user = new User('789465465489', 'TestProvider');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $client->request(
            'PATCH',
            '/fr/album/demo/bulbasaur',
            [],
            [],
            [],
            'yes'
        );

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateNonConnected(): void
    {
        $client = static::createClient();

        $client->request(
            'PATCH',
            '/fr/album/demo/bulbasaur',
            [],
            [],
            [],
            'yes'
        );

        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals(307, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('http://localhost/fr', $crawler->getBaseHref());
    }

    public function testUpdatePremiumCollector(): void
    {
        $client = static::createClient();

        $user = new User('789465465489', 'TestProvider');
        $user->addTrainerRole();
        $user->addCollectorRole();
        $client->loginUser($user, 'web');

        $client->request(
            'PATCH',
            '/fr/album/homepokemongo/bulbasaur',
            [],
            [],
            [],
            'yes'
        );

        $this->assertResponseIsSuccessful();
    }

    public function testUpdatePremiumNonCollector(): void
    {
        $client = static::createClient();

        $user = new User('789465465489', 'TestProvider');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $client->request(
            'PATCH',
            '/fr/album/homepokemongo/bulbasaur',
            [],
            [],
            [],
            'yes'
        );

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testUpdateFailed(): void
    {
        $client = static::createClient();

        $user = new User('789465465489', 'TestProvider');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $client->request(
            'PATCH',
            '/fr/album/demo/blastoise',
            [],
            [],
            [],
            'tobreed'
        );

        $this->assertEquals(500, $client->getResponse()->getStatusCode());

        $content = (string) $client->getResponse()->getContent();
        $this->assertSame('{"error":"Fail to modify resources"}', $content);
    }
}
