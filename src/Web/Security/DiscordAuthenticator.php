<?php

declare(strict_types=1);

namespace App\Web\Security;

class DiscordAuthenticator extends AbstractAuthenticator
{
    protected function getProviderCode(): string
    {
        return 'discord';
    }

    protected function getProviderName(): string
    {
        return 'Discord';
    }
}
