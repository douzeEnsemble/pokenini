<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\CollectionsRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 */
#[CoversClass(CollectionsRepository::class)]
class CollectionsRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetAllSlug(): void
    {
        /** @var CollectionsRepository $repo */
        $repo = static::getContainer()->get(CollectionsRepository::class);

        $list = $repo->getAllSlugs();

        $this->assertEquals(
            [
                'swshdynamaxadventuresbosses',
                'svmassoutbreakspaldea',
                'svmassoutbreakskitakami',
                'svmassoutbreaksterrarium',
                'svtransferable',
                'pogoshadow',
                'pogoshadowshiny',
                'pogodynamax',
            ],
            $list
        );
    }

    public function testGetAll(): void
    {
        /** @var CollectionsRepository $repo */
        $repo = static::getContainer()->get(CollectionsRepository::class);

        $list = $repo->getAll();

        $this->assertCount(8, $list);
    }
}
