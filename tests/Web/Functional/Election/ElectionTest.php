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

        $crawler = $client->request('GET', '/fr/election/demolite');

        $this->assertResponseIsSuccessful();

        $this->assertCountFilter($crawler, 13, '.card');
        $this->assertCountFilter($crawler, 13, '.card-body');
        $this->assertCountFilter($crawler, 12, '.election-card-image-container-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-image-container-shiny');
        $this->assertCountFilter($crawler, 17, '.album-modal-image');
        $this->assertCountFilter($crawler, 0, '.election-card-icon');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-shiny');

        $this->assertEquals(
            'Bulbizarre',
            $crawler->filter('#card-bulbasaur .list-group-item')
                ->eq(0)
                ->text()
        );
        $this->assertCountFilter(
            $crawler,
            1,
            '#card-bulbasaur .list-group-item',
            1,
            '.election-card-type-primary.pokemon-type-grass',
        );
        $this->assertCountFilter(
            $crawler,
            1,
            '#card-bulbasaur .list-group-item',
            1,
            '.election-card-type-secondary.pokemon-type-poison',
        );
        $this->assertEquals(
            'bulbasaur',
            $crawler->filter('#card-bulbasaur input[type="checkbox"][name="winners_slugs[]"]')
                ->attr('value')
        );
        $this->assertEquals(
            'bulbasaur',
            $crawler->filter('#card-bulbasaur input[type="hidden"][name="losers_slugs[]"]')
                ->attr('value')
        );

        $this->assertCountFilter($crawler, 1, '#election-top');
        $this->assertCountFilter($crawler, 5, '#election-top .election-top-item');
        $this->assertCountFilter($crawler, 5, '#election-top .election-top-item img');
        $this->assertCountFilter($crawler, 5, '#election-top .election-top-item strong');

        $this->assertCountFilter($crawler, 1, '#election-actions');
        $this->assertCountFilter($crawler, 4, '#election-actions .nav-item');
        $this->assertEquals(
            'Voir mon top 5 actuel',
            $crawler->filter('#election-actions .nav-item')
                ->eq(0)
                ->text()
        );
        $this->assertEquals(
            "J'ai fait mes choix 0",
            $crawler->filter('#election-actions .nav-item')
                ->eq(1)
                ->text()
        );
        $this->assertEquals(
            'Nouvelle liste',
            $crawler->filter('#election-actions .nav-item')
                ->eq(2)
                ->text()
        );
        $this->assertEquals(
            'Remonter',
            $crawler->filter('#election-actions .nav-item')
                ->eq(3)
                ->text()
        );

        $this->assertCountFilter($crawler, 1, '#election-stats');
        $this->assertSame('Tu as voté 0 fois', $crawler->filter('#election-stats p')->eq(0)->text());
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
            '/fr/election/demolite',
            [
                'election_slug' => '',
                'winners_slugs' => ['pichu'],
                'losers_slugs' => ['pikachu', 'raichu'],
            ],
        );

        $this->assertResponseRedirects();
    }

    public function testEmptyVote(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $user->addTrainerRole();
        $user->addAdminRole();
        $client->loginUser($user, 'web');

        $client->request(
            'POST',
            '/fr/election/demolite',
            [],
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testBadVote(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $user->addTrainerRole();
        $user->addAdminRole();
        $client->loginUser($user, 'web');

        $client->request(
            'POST',
            '/fr/election/demolite',
            [
                'electionSlug' => '',
                'winnersSlugs' => ['pichu'],
                'losersSlugs' => ['pikachu', 'raichu'],
            ],
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testIndexNonTrainer(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $client->loginUser($user, 'web');

        $client->catchExceptions(false);

        $this->expectException(AccessDeniedException::class);

        $client->request('GET', '/fr/election/demolite');
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
            '/fr/election/demolite',
            [],
            [],
            [],
            '{"election_slug": "", "winners_slugs": ["pichu"], "losers_slugs": ["pikachu", "raich"]}'
        );
    }
}
