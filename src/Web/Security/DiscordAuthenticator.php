<?php

declare(strict_types=1);

namespace App\Web\Security;

class DiscordAuthenticator extends AbstractAuthenticator
{
    public function getProviderCode(): string
    {
        return 'discord';
    }

    public function getProviderName(): string
    {
        return 'Discord';
    }
}
