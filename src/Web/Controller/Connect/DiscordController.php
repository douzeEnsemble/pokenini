<?php

declare(strict_types=1);

namespace App\Web\Controller\Connect;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/dd')]
class DiscordController extends AbstractConnectController
{
    protected function getProviderName(): string
    {
        return 'discord';
    }

    protected function getScope(): string
    {
        return 'identify';
    }
}
