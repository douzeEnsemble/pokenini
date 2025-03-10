<?php

declare(strict_types=1);

namespace App\Web\Security;

class FacebookAuthenticator extends AbstractAuthenticator
{
    protected function getProviderCode(): string
    {
        return 'facebook';
    }

    protected function getProviderName(): string
    {
        return 'Facebook';
    }
}
