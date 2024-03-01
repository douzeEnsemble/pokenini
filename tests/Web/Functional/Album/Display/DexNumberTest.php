<?php

declare(strict_types=1);

namespace App\Tests\Web\Functional\Album\Display;

use App\Web\Security\User;
use App\Tests\Web\Common\Traits\TestNavTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DexNumberTest extends WebTestCase
{
    use TestNavTrait;

    public function testDisplayDexNumber(): void
    {
        $client = static::createClient();

        $user = new User('109903422692691643666');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/album/goldsilvercrystal');

        $this->assertCountFilter($crawler, 278, '.album-case');

        $this->assertEquals(
            'Germignon',
            $crawler->filter('.album-case')->first()->filter('.album-case-name')->text()
        );
        $this->assertEquals(
            '#1',
            $crawler->filter('.album-case')->first()->filter('.album-case-dex-number')->text()
        );

        $this->assertEquals(
            'Bulbizarre',
            $crawler->filter('.album-case')->eq(252)->filter('.album-case-name')->text()
        );
        $this->assertEquals(
            '#231',
            $crawler->filter('.album-case')->eq(252)->filter('.album-case-dex-number')->text()
        );
    }
}
