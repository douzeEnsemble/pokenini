<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\VariantForm;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(VariantForm::class)]
class VariantFormTest extends TestCase
{
    public function testGetIdentifierDefault(): void
    {
        $entity = new VariantForm();

        $this->assertNull($entity->getIdentifier());
    }

    public function testConvertToString(): void
    {
        $entity = new VariantForm();
        $entity->name = 'Douze';

        $this->assertEquals('Douze', (string) $entity);
        $this->assertEquals('Douze', $entity->__toString());
    }
}
