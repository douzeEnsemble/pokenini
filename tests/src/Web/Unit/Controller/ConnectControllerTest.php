<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller;

use App\Web\Controller\ConnectController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(ConnectController::class)]
class ConnectControllerTest extends TestCase
{
    public function testLogout(): void
    {
        $controller = new ConnectController();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Don't forget to activate logout in security.yaml");

        $controller->logout();
    }
}
