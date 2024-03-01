<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\GameBundlesRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameBundlesRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetAll(): void
    {
        /** @var GameBundlesRepository $repo */
        $repo = static::getContainer()->get(GameBundlesRepository::class);

        $list = $repo->getAll();

        $this->assertCount(19, $list);
    }
}
