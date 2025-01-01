<?php

declare(strict_types=1);

namespace App\Tests\Web\Functional\Home;

use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Web\Controller\ElectionController;
use App\Web\Security\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @internal
 */
#[CoversClass(ElectionController::class)]
class ElectionTest extends WebTestCase
{
    use TestNavTrait;

    public function testIndex(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/election');

        $this->assertResponseIsSuccessful();

        $this->assertCountFilter($crawler, 3, '.card');
        $this->assertCountFilter($crawler, 3, '.card-body');
        $this->assertCountFilter($crawler, 3, '.election-card-image-container-regular');
        $this->assertCountFilter($crawler, 3, '.election-card-image-container-shiny[hidden]');
        $this->assertCountFilter($crawler, 6, '.album-modal-image');
        $this->assertCountFilter($crawler, 6, '.election-card-icon');
        $this->assertCountFilter($crawler, 3, '.election-card-icon-regular.active');
        $this->assertCountFilter($crawler, 3, '.election-card-icon-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-shiny.active');
        $this->assertCountFilter($crawler, 3, '.election-card-icon-shiny');

        $this->assertEquals(
            'Bulbizarre / Bulbasaur', 
            $crawler->filter('#card-bulbasaur .list-group-item')
                ->eq(0)
                ->text()
        );
        $this->assertEquals(
            'Forme Normale', 
            $crawler->filter('#card-bulbasaur .list-group-item')
                ->eq(1)
                ->text()
        );
        $this->assertCountFilter(
            $crawler,
            1,
            "#card-bulbasaur .list-group-item",
            2,
            '.election-card-type-primary.pokemon-type-grass',
        );
        $this->assertCountFilter(
            $crawler,
            1,
            "#card-bulbasaur .list-group-item",
            2,
            '.election-card-type-secondary.pokemon-type-poison',
        );
        $this->assertEquals(
            'Numéro de dex national 1', 
            $crawler->filter('#card-bulbasaur .list-group-item')
                ->eq(3)
                ->text()
        );
        $this->assertEquals(
            'bulbasaur', 
            $crawler->filter('#card-bulbasaur button.election-vote-action[name="winner_slug"]')
                ->attr('value')
        );
        $this->assertEquals(
            'bulbasaur', 
            $crawler->filter('#card-bulbasaur input[type="hidden"][name="losers_slugs[]"]')
                ->attr('value')
        );
    }

    public function testVote(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $user->addTrainerRole();
        $user->addAdminRole();
        $client->loginUser($user, 'web');

        $client->request(
            'POST',
            '/fr/election',
            [
                'election_slug' => '',
                'winner_slug' => 'pichu',
                'losers_slugs' => ['pikachu', 'raichu'],
            ],
        );

        $this->assertResponseRedirects();
    }

    public function testIndexNonTrainer(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $client->loginUser($user, 'web');

        $client->catchExceptions(false);

        $this->expectException(AccessDeniedException::class);

        $client->request('GET', '/fr/election');
    }

    public function testVoteNonTrainer(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $client->loginUser($user, 'web');

        $client->catchExceptions(false);

        $this->expectException(AccessDeniedException::class);

        $client->request(
            'POST',
            '/fr/election',
            [],
            [],
            [],
            '{"election_slug": "", "winner_slug": "pichu", "losers_slugs": ["pikachu", "raich"]}'
        );
    }
}
