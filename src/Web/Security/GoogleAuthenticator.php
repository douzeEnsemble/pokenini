<?php

declare(strict_types=1);

namespace App\Web\Security;

class GoogleAuthenticator extends AbstractAuthenticator
{
    public function getProviderCode(): string
    {
        return 'google';
    }

    public function getProviderName(): string
    {
        return 'Google';
    }
}
