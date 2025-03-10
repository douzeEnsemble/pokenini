<?php

declare(strict_types=1);

namespace App\Web\Controller\Connect;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/p')]
class PassageController extends AbstractConnectController
{
    protected function getProviderName(): string
    {
        return 'passage';
    }

    protected function getScope(): string
    {
        return 'openid';
    }
}
