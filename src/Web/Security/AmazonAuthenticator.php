<?php

declare(strict_types=1);

namespace App\Web\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Luchianenco\OAuth2\Client\Provider\AmazonResourceOwner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class AmazonAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    use AuthenticatorTrait;

    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly RouterInterface $router,
        private readonly string $listAdmin,
        private readonly string $listTrainer,
        private readonly string $listCollector,
        private readonly bool $isInvitationRequired,
    ) {}

    public function supports(Request $request): ?bool
    {
        return 'app_web_connect_amazon_check' === $request->attributes->get('_route');
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('amazon');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var AmazonResourceOwner $authUser */
                $authUser = $client->fetchUserFromToken($accessToken);

                /** @var string $userId */
                $userId = $authUser->getId();

                return $this->loadUserFromLists($userId, 'Amazon');
            })
        );
    }
}
