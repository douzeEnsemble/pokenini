<?php

declare(strict_types=1);

namespace App\Web\Security;

class PassageAuthenticator extends AbstractAuthenticator
{
    #[\Override]
    protected function getProviderCode(): string
    {
        return 'passage';
    }

    #[\Override]
    protected function getProviderName(): string
    {
        return 'Passage';
    }
}
