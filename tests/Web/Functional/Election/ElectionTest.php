<?php

declare(strict_types=1);

namespace App\Tests\Web\Functional\Home;

use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Web\Controller\ElectionController;
use App\Web\Security\User;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
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

        $this->assertSame('Fait ton choix', $crawler->filter('h1')->text());

        $this->assertCountFilter($crawler, 13, '.card');
        $this->assertCountFilter($crawler, 13, '.card-body');
        $this->assertCountFilter($crawler, 12, '.election-card-image-container-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-image-container-shiny');
        $this->assertCountFilter($crawler, 17, '.album-modal-image');
        $this->assertCountFilter($crawler, 0, '.election-card-icon');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-shiny');

        $this->assertCardContentDemoLite($crawler);
        $this->assertElectionTop($crawler);
        $this->assertActions($crawler);
        $this->assertStats(
            $crawler,
            1,
            5,
            20,
            "Tu n'as pas de favoris qui se détache.",
        );
    }

    public function testIndexShinyDex(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/election/demoliteshiny');

        $this->assertResponseIsSuccessful();

        $this->assertSame('Fait ton choix', $crawler->filter('h1')->text());

        $this->assertCountFilter($crawler, 13, '.card');
        $this->assertCountFilter($crawler, 13, '.card-body');
        $this->assertCountFilter($crawler, 0, '.election-card-image-container-regular');
        $this->assertCountFilter($crawler, 12, '.election-card-image-container-shiny');
        $this->assertCountFilter($crawler, 17, '.album-modal-image');
        $this->assertCountFilter($crawler, 0, '.election-card-icon');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-shiny');

        $this->assertCardContentDemoLite($crawler);
        $this->assertElectionTop($crawler);
        $this->assertActions($crawler);
        $this->assertStats(
            $crawler,
            7,
            5,
            140,
            "Tu n'as pas de favoris qui se détache.",
        );
    }

    public function testIndexWithoutDisplayForm(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/election/mega');

        $this->assertResponseIsSuccessful();

        $this->assertSame('Fait ton choix', $crawler->filter('h1')->text());

        $this->assertCountFilter($crawler, 13, '.card');
        $this->assertCountFilter($crawler, 13, '.card-body');
        $this->assertCountFilter($crawler, 12, '.election-card-image-container-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-image-container-shiny');
        $this->assertCountFilter($crawler, 17, '.album-modal-image');
        $this->assertCountFilter($crawler, 0, '.election-card-icon');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-shiny');

        $this->assertCardContentMega($crawler);
        $this->assertElectionTop($crawler);
        $this->assertActions($crawler);
        $this->assertStats(
            $crawler,
            5,
            5,
            100,
            "Tu n'as pas de favoris qui se détache.",
        );
    }

    public function testIndexDetachedCount(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/election/mega/favorite');

        $this->assertResponseIsSuccessful();

        $this->assertSame('Fait ton choix', $crawler->filter('h1')->text());

        $this->assertCountFilter($crawler, 13, '.card');
        $this->assertCountFilter($crawler, 13, '.card-body');
        $this->assertCountFilter($crawler, 12, '.election-card-image-container-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-image-container-shiny');
        $this->assertCountFilter($crawler, 17, '.album-modal-image');
        $this->assertCountFilter($crawler, 0, '.election-card-icon');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-shiny');

        $this->assertCardContentMega($crawler);
        $this->assertElectionTop($crawler);
        $this->assertActions($crawler);
        $this->assertStats(
            $crawler,
            7,
            5,
            140,
            'Tu as 1 favori qui se détache',
        );
    }

    public function testIndexVote(): void
    {
        $client = static::createClient();

        $user = new User('789465465489');
        $user->addTrainerRole();
        $client->loginUser($user, 'web');

        $crawler = $client->request('GET', '/fr/election/mega/vote');

        $this->assertResponseIsSuccessful();

        $this->assertSame('Vote maintenant', $crawler->filter('h1')->text());

        $this->assertCountFilter($crawler, 13, '.card');
        $this->assertCountFilter($crawler, 13, '.card-body');
        $this->assertCountFilter($crawler, 12, '.election-card-image-container-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-image-container-shiny');
        $this->assertCountFilter($crawler, 17, '.album-modal-image');
        $this->assertCountFilter($crawler, 0, '.election-card-icon');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-regular');
        $this->assertCountFilter($crawler, 0, '.election-card-icon-shiny');

        $this->assertCardContentMega($crawler);
        $this->assertElectionTop($crawler);
        $this->assertActions($crawler);
        $this->assertStats(
            $crawler,
            4,
            8,
            50,
            'Tu as 1 favori qui se détache',
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
            '/fr/election/demolite',
            [
                'winners_slugs' => ['pichu'],
                'losers_slugs' => ['pikachu', 'raichu'],
            ],
        );

        $this->assertResponseRedirects('/fr/election/demolite');

        $crawler = $client->followRedirect();

        $this->assertStats(
            $crawler,
            1,
            5,
            20,
            "Tu n'as pas de favoris qui se détache.",
        );
    }

    public function testVoteWithElectionSlug(): void
    {
        $client = static::createClient();

        $user = new User('8764532');
        $user->addTrainerRole();
        $user->addAdminRole();
        $client->loginUser($user, 'web');

        $client->request(
            'POST',
            '/fr/election/demolite/favorite',
            [
                'winners_slugs' => ['pichu'],
                'losers_slugs' => ['pikachu', 'raichu'],
            ],
        );

        $this->assertResponseRedirects('/fr/election/demolite/favorite');

        $crawler = $client->followRedirect();

        $this->assertSame('Fait ton choix', $crawler->filter('h1')->text());

        $this->assertStats(
            $crawler,
            6,
            8,
            75,
            "Tu n'as pas de favoris qui se détache.",
        );
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
            [],
            [],
            '',
        );

        $this->assertResponseStatusCodeSame(400);

        $content = (string) $client->getResponse()->getContent();
        $this->assertStringContainsString('Data cannot be empty', $content);
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
            [],
            [],
            [],
            http_build_query([
                'electionSlug' => '',
                'winnersSlugs' => ['pichu'],
                'losersSlugs' => ['pikachu', 'raichu'],
            ]),
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
            '{"winners_slugs": ["pichu"], "losers_slugs": ["pikachu", "raich"]}'
        );
    }

    private function assertCardContentDemoLite(Crawler $crawler): void
    {
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
    }

    private function assertCardContentMega(Crawler $crawler): void
    {
        $this->assertEquals(
            'Dracaufeu',
            $crawler->filter('#card-charizard-mega-y .list-group-item')
                ->eq(0)
                ->text()
        );
        $this->assertCountFilter(
            $crawler,
            1,
            '#card-charizard-mega-y .list-group-item',
            1,
            '.election-card-type-primary.pokemon-type-fire',
        );
        $this->assertCountFilter(
            $crawler,
            1,
            '#card-charizard-mega-y .list-group-item',
            1,
            '.election-card-type-secondary.pokemon-type-flying',
        );
        $this->assertEquals(
            'charizard-mega-y',
            $crawler->filter('#card-charizard-mega-y input[type="checkbox"][name="winners_slugs[]"]')
                ->attr('value')
        );
        $this->assertEquals(
            'charizard-mega-y',
            $crawler->filter('#card-charizard-mega-y input[type="hidden"][name="losers_slugs[]"]')
                ->attr('value')
        );
    }

    private function assertElectionTop(Crawler $crawler): void
    {
        $this->assertCountFilter($crawler, 1, '#election-top');
        $this->assertCountFilter($crawler, 5, '#election-top .election-top-item');
        $this->assertCountFilter($crawler, 5, '#election-top .election-top-item img');
        $this->assertCountFilter($crawler, 5, '#election-top .election-top-item strong');
    }

    private function assertActions(Crawler $crawler): void
    {
        $this->assertCountFilter($crawler, 1, '#election-actions-top');
        $this->assertCountFilter($crawler, 0, '#election-actions-top .election-actions-item');
        $this->assertCountFilter($crawler, 1, '#election-actions-top .progress');

        $this->assertCountFilter($crawler, 1, '#election-actions-bottom');
        $this->assertCountFilter($crawler, 3, '#election-actions-bottom .election-actions-item');
        $index = 0;
        $this->assertEquals(
            'Nouvelle liste',
            $crawler->filter('#election-actions-bottom .election-actions-item')
                ->eq($index++)
                ->text()
        );
        $this->assertEquals(
            "J'ai fait mes choix 0",
            $crawler->filter('#election-actions-bottom .election-actions-item')
                ->eq($index++)
                ->text()
        );
        $this->assertEquals(
            'Voir les filtres',
            $crawler->filter('#election-actions-bottom .election-actions-item')
                ->eq($index++)
                ->text()
        );
    }

    private function assertStats(
        Crawler $crawler,
        int $roundCount,
        int $totalRoundCount,
        int $progress,
        string $favoriteCountText,
    ): void {
        $this->assertCountFilter($crawler, 1, '#election-stats');

        $this->assertSame(
            "{$progress}%",
            $crawler->filter('#election-actions-top div.progress')->eq(0)->text()
        );

        $roundsTxt = 1 >= $roundCount ? 'tour' : 'tours';

        $this->assertSame(
            "Tu as fait <strong>{$roundCount}</strong> {$roundsTxt} sur <strong>{$totalRoundCount}</strong>.",
            $crawler->filter('#election-actions-top div.progress .progress-bar')->eq(0)->attr('data-bs-title')
        );

        $this->assertSame(
            "Tu as fait {$roundCount} {$roundsTxt} sur {$totalRoundCount}.",
            $crawler->filter('#election-stats p')->eq(0)->text()
        );
        $this->assertSame(
            $favoriteCountText,
            $crawler->filter('#election-stats p')->eq(1)->text()
        );
    }
}
