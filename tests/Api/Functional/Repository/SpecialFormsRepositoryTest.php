<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Repository;

use App\Api\Repository\SpecialFormsRepository;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SpecialFormsRepositoryTest extends KernelTestCase
{
    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testGetAll(): void
    {
        /** @var SpecialFormsRepository $repo */
        $repo = static::getContainer()->get(SpecialFormsRepository::class);

        $list = $repo->getAll();

        $this->assertCount(4, $list);
    }
}
