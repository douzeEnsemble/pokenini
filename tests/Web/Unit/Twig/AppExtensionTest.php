<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Twig;

use App\Web\Twig\AppExtension;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Twig\TwigFilter;

/**
 * @internal
 */
#[CoversClass(AppExtension::class)]
class AppExtensionTest extends TestCase
{
    public function testGetFilters(): void
    {
        $extension = new AppExtension();

        $filters = $extension->getFilters();

        $this->assertCount(1, $filters);

        /** @var TwigFilter $ksortFilter */
        $ksortFilter = $filters[0];

        $this->assertInstanceOf(TwigFilter::class, $ksortFilter);
        $this->assertEquals('ksort', $ksortFilter->getName());

        /** @var mixed[] $ksortFilterCallable */
        $ksortFilterCallable = $ksortFilter->getCallable();
        $this->assertCount(2, $ksortFilterCallable);
        $this->assertInstanceOf(AppExtension::class, $ksortFilterCallable[0]);
        $this->assertEquals('ksort', $ksortFilterCallable[1]);
    }

    public function testKsort(): void
    {
        $extension = new AppExtension();

        $data = [
            'b' => 1,
            'a' => 2,
            'c' => 3,
        ];

        $this->assertSame(
            [
                'a' => 2,
                'b' => 1,
                'c' => 3,
            ],
            $extension->ksort($data)
        );
    }
}
