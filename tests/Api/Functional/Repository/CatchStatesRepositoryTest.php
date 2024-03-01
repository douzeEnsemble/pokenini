<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\CatchStatesRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CatchStatesRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetAll(): void
    {
        /** @var CatchStatesRepository $repo */
        $repo = static::getContainer()->get(CatchStatesRepository::class);

        $list = $repo->getAll();

        $this->assertCount(4, $list);
    }
}
