<?php

declare(strict_types=1);

namespace App\Web\Service;

use App\Web\Security\User;
use App\Web\Security\UserTokenService;
use App\Web\Service\Api\GetDexService;
use Symfony\Bundle\SecurityBundle\Security;

class GetDexByRoleService
{
    public function __construct(
        private readonly GetDexService $getDexService,
        private readonly UserTokenService $userTokenService,
        private Security $security,
    ) {}

    /**
     * @return string[][]
     */
    public function getUserDex(): array
    {
        /** @var ?User $user */
        $user = $this->security->getUser();

        if (!$user) {
            return [];
        }

        $userToken = $this->userTokenService->getLoggedUserToken();

        return $user->isAnAdmin()
            ? $this->getDexService->getWithUnreleasedAndPremium($userToken)
            : $this->getDexService->get($userToken);
    }
}
