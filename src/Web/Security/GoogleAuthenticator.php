<?php

declare(strict_types=1);

namespace App\Web\Security;

class GoogleAuthenticator extends AbstractAuthenticator
{
    protected function getProviderCode(): string
    {
        return 'google';
    }

    protected function getProviderName(): string
    {
        return 'Google';
    }
}
