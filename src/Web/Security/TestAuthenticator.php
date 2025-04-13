<?php

declare(strict_types=1);

namespace App\Web\Security;

use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TestAuthenticator extends OAuth2Authenticator
{
    use AuthenticatorTrait;

    public function __construct(
        private readonly RouterInterface $router,
        private readonly string $listAdmin,
        private readonly string $listTrainer,
        private readonly string $listCollector,
        private readonly bool $isInvitationRequired,
    ) {}

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-TEST-USER') 
            && 'app_web_connect_test_check' === $request->attributes->get('_route')
        ;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function authenticate(Request $request): Passport
    {
        /** @var User $user */
        $user = $request->headers->get('X-TEST-USER');

        return new SelfValidatingPassport(
            new UserBadge($user->getId(), function () use ($user) {
                return $user;
            })
        );
    }
}
