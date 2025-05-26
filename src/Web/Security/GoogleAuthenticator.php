<?php

declare(strict_types=1);

namespace App\Web\Security;

class GoogleAuthenticator extends AbstractAuthenticator
{
    #[\Override]
    protected function getProviderCode(): string
    {
        return 'google';
    }

    #[\Override]
    protected function getProviderName(): string
    {
        return 'Google';
    }
}
