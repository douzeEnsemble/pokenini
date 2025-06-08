<?php

declare(strict_types=1);

namespace App\Tests\Web\Functional\Album\Template;

use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Web\Controller\AlbumIndexController;
use App\Web\Security\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(AlbumIndexController::class)]
class List5Test extends WebTestCase
{
    use TestNavTrait;

    public function testDexList5Template(): void
    {
        $client = static::createClient();

        $user = new User('12', 'TestProvider');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/album/demolist5?t=7b52009b64fd0a2a49e6d8a939753077792b0554');

        $this->assertCountFilter($crawler, 41, '.album-case.col');
        $this->assertCountFilter($crawler, 5, 'div.row.album-line', 0, '.album-case.col');
        $this->assertCountFilter($crawler, 5, 'div.row.album-line', 2, '.album-case.col');
        $this->assertCountFilter($crawler, 9, 'div.row.album-line');
        $this->assertCountFilter($crawler, 0, '.box');
    }

    public function testFilterDexList5Template(): void
    {
        $client = static::createClient();

        $user = new User('12', 'TestProvider');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/album/demolist5?cs=no&t=7b52009b64fd0a2a49e6d8a939753077792b0554');

        $this->assertCountFilter($crawler, 35, '.album-case.col');
        $this->assertCountFilter($crawler, 5, 'div.row.album-line', 0, '.album-case.col');
        $this->assertCountFilter($crawler, 5, 'div.row.album-line', 2, '.album-case.col');
        $this->assertCountFilter($crawler, 7, 'div.row.album-line');
        $this->assertCountFilter($crawler, 0, '.box');
    }
}
