<?php

declare(strict_types=1);

namespace App\Api\ActionStarter;

use App\Api\Message\ActionMessageInterface;

interface ActionStarterInterface
{
    public function start(): ActionMessageInterface;
}
