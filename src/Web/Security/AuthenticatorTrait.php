<?php

namespace App\Web\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

trait AuthenticatorTrait
{
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

    private function loadUserFromLists(string $identifier): User
    {
        $user = new User($identifier);

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
    }
}
