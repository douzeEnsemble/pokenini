<?php

declare(strict_types=1);

namespace App\Web\Security;

class DiscordAuthenticator extends AbstractAuthenticator
{
    #[\Override]
    protected function getProviderCode(): string
    {
        return 'discord';
    }

    #[\Override]
    protected function getProviderName(): string
    {
        return 'Discord';
    }
}
