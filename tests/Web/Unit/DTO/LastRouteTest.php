<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\DTO;

use App\Web\DTO\LastRoute;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class LastRouteTest extends TestCase
{
    public function testConstruct(): void
    {
        $lastRoute = new LastRoute('douze', ['un' => '1', 'deux' => '2']);

        $this->assertEquals('douze', $lastRoute->route);
        $this->assertEquals(['un' => '1', 'deux' => '2'], $lastRoute->routeParams);
    }
}
