<?php

declare(strict_types=1);

namespace App\Tests\Web\Functional\Home;

use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Web\Controller\HomeController;
use App\Web\Security\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(HomeController::class)]
class HomeTest extends WebTestCase
{
    use TestNavTrait;

    public function testHome(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr');

        $this->assertResponseIsSuccessful();

        $this->assertFrenchLangSwitch($crawler);

        $this->assertCountFilter($crawler, 6, '.home-item');
        $this->assertCountFilter($crawler, 6, '.home-item h5');
        $this->assertCountFilter($crawler, 1, '.home-item h6');

        $this->assertCountFilter($crawler, 2, '.dex_is_premium');
        $this->assertCountFilter($crawler, 0, '.dex_not_is_released');
        $this->assertCountFilter($crawler, 1, '.dex_is_custom');

        $firstAlbum = $crawler->filter('.home-item')->first();
        $this->assertEquals('Épée, Bouclier', $firstAlbum->text());
        $this->assertEquals('/fr/album/swordshield', $firstAlbum->filter('a')->attr('href'));
        $this->assertEquals('https://icon.pokenini.fr/banner/swordshield.png', $firstAlbum->filter('img')->attr('src'));

        $secondAlbum = $crawler->filter('.home-item')->eq(2);
        $this->assertEquals('Home Chromatique', $secondAlbum->text());
        $this->assertEquals('/fr/album/homeshiny', $secondAlbum->filter('a')->attr('href'));
        $this->assertEquals('https://icon.pokenini.fr/banner/homeshiny.png', $secondAlbum->filter('img')->attr('src'));

        $this->assertCountFilter($crawler, 0, 'script[src="/js/album.js"]');

        $this->assertStringNotContainsString('const catchStates = JSON.parse', $crawler->outerHtml());
        $this->assertStringNotContainsString('watchCatchStates();', $crawler->outerHtml());
    }

    public function testHomeAsAdmin(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $user->addTrainerRole();
        $user->addAdminRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr');

        $this->assertResponseIsSuccessful();

        $this->assertFrenchLangSwitch($crawler);

        $this->assertCountFilter($crawler, 6, '.home-item');
        $this->assertCountFilter($crawler, 6, '.home-item h5');
        $this->assertCountFilter($crawler, 0, '.home-item h6');

        $this->assertCountFilter($crawler, 3, '.dex_is_premium');
        $this->assertCountFilter($crawler, 1, '.dex_not_is_released');
        $this->assertCountFilter($crawler, 0, '.dex_is_custom');

        $firstAlbum = $crawler->filter('.home-item')->first();
        $this->assertEquals('Rouge, Vert, Bleu, Jaune', $firstAlbum->text());
        $this->assertEquals('/fr/album/redgreenblueyellow', $firstAlbum->filter('a')->attr('href'));
        $this->assertEquals('https://icon.pokenini.fr/banner/redgreenblueyellow.png', $firstAlbum->filter('img')->attr('src'));

        $thirdAlbum = $crawler->filter('.home-item')->eq(3);
        $this->assertEquals('Home Chromatique', $thirdAlbum->text());
        $this->assertEquals('/fr/album/homeshiny', $thirdAlbum->filter('a')->attr('href'));
        $this->assertEquals('https://icon.pokenini.fr/banner/homeshiny.png', $thirdAlbum->filter('img')->attr('src'));

        $this->assertCountFilter($crawler, 0, 'script[src="/js/album.js"]');

        $this->assertStringNotContainsString('const catchStates = JSON.parse', $crawler->outerHtml());
        $this->assertStringNotContainsString('watchCatchStates();', $crawler->outerHtml());
    }

    public function testNonConnectedHome(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/fr');

        $this->assertResponseIsSuccessful();

        $this->assertCountFilter($crawler, 8, '.home-item');
        $this->assertCountFilter($crawler, 8, '.home-item h5');
        $this->assertCountFilter($crawler, 1, '.home-item h6');

        $this->assertCountFilter($crawler, 1, '.dex_is_premium');
        $this->assertCountFilter($crawler, 0, '.dex_not_is_released');
        $this->assertCountFilter($crawler, 0, '.dex_is_custom');

        $dex = [
            'home',
            'homeshiny',
            'swordshieldapriball',
            'pokemonlegendsarceuspokeball',
            'scarletviolet',
            'newmonsscarletviolet',
            'pokemongoshiny',
            'totem',
        ];

        for ($i = 0; $i < 7; ++$i) {
            $dexSlug = $dex[$i];

            $this->assertEquals(
                "/fr/album/{$dexSlug}?t=0a286c2c78b485e1bcecf68febbda17084d0b2be",
                $crawler->filter('.home-item')->eq($i)->filter('a')->attr('href')
            );
            $this->assertEquals(
                "https://icon.pokenini.fr/banner/{$dexSlug}.png",
                $crawler->filter('.home-item')->eq($i)->filter('img')->attr('src')
            );
        }
    }

    public function testConnectedHomeNoDex(): void
    {
        $client = static::createClient();

        $user = new User('0');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr');

        $this->assertResponseIsSuccessful();

        $this->assertCountFilter($crawler, 0, '.home-item');
        $this->assertCountFilter($crawler, 1, '.alert');
    }

    public function testConnectedHomeDexNoOnHome(): void
    {
        $client = static::createClient();

        $user = new User('1');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr');

        $this->assertResponseIsSuccessful();

        $this->assertCountFilter($crawler, 0, '.home-item');
        $this->assertCountFilter($crawler, 1, '.alert');
        $this->assertCountFilter($crawler, 1, '.alert a');
    }

    public function testConnectedHomeSomeDex(): void
    {
        $client = static::createClient();

        $user = new User('2');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr');

        $this->assertResponseIsSuccessful();

        $this->assertCountFilter($crawler, 2, '.home-item');
        $this->assertCountFilter($crawler, 2, '.home-item h5');
        $this->assertCountFilter($crawler, 0, '.home-item h6');
    }

    public function testHomeFrench(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr?t=7b52009b64fd0a2a49e6d8a939753077792b0554');

        $this->assertResponseIsSuccessful();

        $this->assertFrenchLangSwitch($crawler);

        $this->assertCountFilter($crawler, 6, '.home-item');
        $this->assertCountFilter($crawler, 6, '.home-item h5');
        $this->assertCountFilter($crawler, 1, '.home-item h6');

        $firstAlbum = $crawler->filter('.home-item')->first();
        $this->assertEquals('Épée, Bouclier', $firstAlbum->text());
        $this->assertEquals('/fr/album/swordshield', $firstAlbum->filter('a')->attr('href'));

        $secondAlbum = $crawler->filter('.home-item')->eq(2);
        $this->assertEquals('Home Chromatique', $secondAlbum->text());
        $this->assertEquals('/fr/album/homeshiny', $secondAlbum->filter('a')->attr('href'));
    }

    public function testHomeEnglish(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/en?t=7b52009b64fd0a2a49e6d8a939753077792b0554');

        $this->assertResponseIsSuccessful();

        $this->assertEnglishLangSwitch($crawler);

        $this->assertCountFilter($crawler, 6, '.home-item');
        $this->assertCountFilter($crawler, 6, '.home-item h5');
        $this->assertCountFilter($crawler, 1, '.home-item h6');

        $firstAlbum = $crawler->filter('.home-item')->first();
        $this->assertEquals('Sword, Shield', $firstAlbum->text());
        $this->assertEquals('/en/album/swordshield', $firstAlbum->filter('a')->attr('href'));

        $secondAlbum = $crawler->filter('.home-item')->eq(2);
        $this->assertEquals('Home Shiny', $secondAlbum->text());
        $this->assertEquals('/en/album/homeshiny', $secondAlbum->filter('a')->attr('href'));
    }
}
