<?php

declare(strict_types=1);

namespace App\Web\Controller\Connect;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/fb')]
class FacebookController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function goto(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect(['openid'], [])
        ;
    }

    // @codeCoverageIgnoreStart
    #[Route('/c', methods: ['GET'])]
    public function check(): void
    {
        // noting, all done by the authenticator
    }
    // @codeCoverageIgnoreEnd
}
