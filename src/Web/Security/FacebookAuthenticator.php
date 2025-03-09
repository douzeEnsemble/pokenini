<?php

declare(strict_types=1);

namespace App\Web\Security;

class FacebookAuthenticator extends AbstractAuthenticator
{
    public function getProviderCode(): string
    {
        return 'facebook';
    }

    public function getProviderName(): string
    {
        return 'Facebook';
    }
}
