<?php

declare(strict_types=1);

namespace App\Tests\Api\Unit\Entity;

use App\Api\Entity\ActionLog;
use PHPUnit\Framework\TestCase;

class ActionLogTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $messageAction = new ActionLog('alpha');

        $this->assertSame('alpha', $messageAction->getType());
    }
}
