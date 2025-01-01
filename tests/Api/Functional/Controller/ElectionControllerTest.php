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
            '{"trainer_external_id": "12", "election_slug": "", "winner_slug": "pichu", "losers_slugs": ["pikachu", "raichu"]}'
        );

        $this->assertResponseStatusCodeSame(200);

        $content = (string) $client->getResponse()->getContent();

        /** @var int[]|int[][] $data */
        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(
            [
                'winnerFinalElo' => 16,
                'losersElo' => [
                    'pikachu' => -16,
                    'raichu' => -16,
                ],
            ],
            $data,
        );
    }
}
