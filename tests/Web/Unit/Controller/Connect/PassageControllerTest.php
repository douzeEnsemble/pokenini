<?php

declare(strict_types=1);

namespace App\Tests\Web\Unit\Controller\Connect;

use App\Web\Controller\Connect\PassageController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(PassageController::class)]
class PassageControllerTest extends TestCase
{
    use ConnectControllerTestTrait;

    public function testGoto(): void
    {
        $controller = new PassageController();

        $this->assertGoto($controller, 'openid', 'passage');
    }
}
