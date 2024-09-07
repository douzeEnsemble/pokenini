<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\RegionsRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class RegionsRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetAll(): void
    {
        /** @var RegionsRepository $repo */
        $repo = static::getContainer()->get(RegionsRepository::class);

        $list = $repo->getAllSlugs();

        $this->assertEquals(
            [
                'kanto',
                'johto',
                'hoenn',
                'sinnoh',
                'unova',
                'kalos',
                'alola',
                'galar',
                'hisui',
                'paldea',
            ],
            $list
        );
    }
}
