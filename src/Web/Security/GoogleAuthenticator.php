<?php

declare(strict_types=1);

namespace App\Web\Security;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class GoogleAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly RouterInterface $router,
        private readonly string $listAdmin,
        private readonly string $listTrainer,
        private readonly string $listCollector,
    ) {}

    public function supports(Request $request): ?bool
    {
        return 'app_web_connect_googlecheck' === $request->attributes->get('_route');
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var GoogleUser $authUser */
                $authUser = $client->fetchUserFromToken($accessToken);

                /** @var string $userId */
                $userId = $authUser->getId();

                $user = new User($userId);

                $listAdmins = explode(',', $this->listAdmin);
                $listAdmins = array_map(fn ($value) => trim($value), $listAdmins);
                $listTrainers = explode(',', $this->listTrainer);
                $listTrainers = array_map(fn ($value) => trim($value), $listTrainers);
                $listCollectors = explode(',', $this->listCollector);
                $listCollectors = array_map(fn ($value) => trim($value), $listCollectors);

                if (in_array($user->getUserIdentifier(), $listAdmins)) {
                    $user->addAdminRole();
                }
                if (in_array($user->getUserIdentifier(), $listTrainers)) {
                    $user->addTrainerRole();
                }
                if (in_array($user->getUserIdentifier(), $listCollectors)) {
                    $user->addCollectorRole();
                }

                return $user;
            })
        );
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();

        $targetUrl = $this->router->generate('app_web_outerroom_index');
        if ($user->isATrainer()) {
            $targetUrl = $this->router->generate('app_web_home_index');
        }

        return new RedirectResponse($targetUrl);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * {@inheritDoc}
     */
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            $this->router->generate('app_web_home_index'),
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
