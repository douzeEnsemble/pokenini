<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\GamesRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GamesRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetAll(): void
    {
        /** @var GamesRepository $repo */
        $repo = static::getContainer()->get(GamesRepository::class);

        $list = $repo->getAllSlugs();

        $this->assertEquals(
            [
                'red',
                'green',
                'blue',
                'yellow',
                'gold',
                'silver',
                'crystal',
                'ruby',
                'sapphire',
                'firered',
                'leafgreen',
                'emerald',
                'diamond',
                'pearl',
                'platinium',
                'heartgold',
                'soulsilver',
                'black',
                'white',
                'black2',
                'white2',
                'x',
                'y',
                'omegaruby',
                'alphasapphire',
                'sun',
                'moon',
                'ultrasun',
                'ultramoon',
                'letsgopikachu',
                'letsgoeevee',
                'sword',
                'shield',
                'brilliantdiamond',
                'shiningpearl',
                'pokemonlegendsarceus',
                'scarlet',
                'violet',
            ],
            $list
        );
    }
}
