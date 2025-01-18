<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Controller;

use App\Api\Controller\DexCanHoldElectionController;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(DexCanHoldElectionController::class)]
class DexCanHoldElectionControllerTest extends WebTestCase
{
    use RefreshDatabaseTrait;

    public function testGet(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            'api/dex/can_hold_election',
            [
                'include_unreleased_dex' => true,
                'include_premium_dex' => true,
            ],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(200);

        $content = (string) $client->getResponse()->getContent();

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame(
            [
                [
                    'slug' => 'redgreenblueyellow',
                    'original_slug' => 'redgreenblueyellow',
                    'name' => 'Red / Green / Blue / Yellow',
                    'french_name' => 'Rouge / Vert / Bleu / Jaune',
                    'is_shiny' => false,
                    'is_display_form' => true,
                    'description' => 'The list of obtainable Pokémons in Red, Blue, Yellow and even Green games',
                    'french_description' => 'La liste des pokémons obtenable dans les jeux Rouge, Bleu, Jaune et même Vert.',
                    'is_released' => true,
                    'is_premium' => false,
                    'dex_total_count' => 7,
                ],
                [
                    'slug' => 'home',
                    'original_slug' => 'home',
                    'name' => 'Home',
                    'french_name' => 'Home',
                    'is_shiny' => false,
                    'is_display_form' => true,
                    'description' => '',
                    'french_description' => '',
                    'is_released' => true,
                    'is_premium' => false,
                    'dex_total_count' => 22,
                ],
            ],
            $data
        );
    }

    public function testGetEmpty(): void
    {
        $client = static::createClient();

        $client->request(
            'GET',
            'api/dex/can_hold_election',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'web',
                'PHP_AUTH_PW' => 'douze',
            ],
        );

        $this->assertResponseStatusCodeSame(200);

        $content = (string) $client->getResponse()->getContent();

        $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame([], $data);
    }
}
