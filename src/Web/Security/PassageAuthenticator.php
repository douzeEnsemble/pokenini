<?php

declare(strict_types=1);

namespace App\Web\Security;

class PassageAuthenticator extends AbstractAuthenticator
{
    protected function getProviderCode(): string
    {
        return 'passage';
    }

    protected function getProviderName(): string
    {
        return 'Passage';
    }
}
