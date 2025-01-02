<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\ElectionController;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(ElectionController::class)]
class ElectionControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    public function testVote(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            'api/election/vote',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
            '{"trainer_external_id": "12", "election_slug": "", "winner_slug": "butterfree", "losers_slugs": ["caterpie", "metapod"]}',
        );

        $this->assertResponseStatusCodeSame(200);

        $content = (string) $client->getResponse()->getContent();

        /** @var int[]|int[][] $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(
            [
                'winnerFinalElo' => 1031,
                'losersElo' => [
                    'caterpie' => 984,
                    'metapod' => 985,
                ],
            ],
            $data,
        );
    }

    public function testEmptyVote(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            'api/election/vote',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
            '',
        );

        $this->assertResponseStatusCodeSame(400);
    }

    public function testBadVote(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            'api/election/vote',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
            '{"trainerExternalId": "12", "electionSlug": "", "winnerSlug": "pichu", "losersSlugs": ["pikachu", "raichu"]}',
        );

        $this->assertResponseStatusCodeSame(400);
    }
}
