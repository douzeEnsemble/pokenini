<?php

declare(strict_types=1);

namespace App\Web\Controller;

use App\Web\DTO\AdminAction;
use App\Web\Service\Api\AdminActionService;
use App\Web\Service\CacheInvalidatorService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/istration/action')]
class AdminActionController extends AbstractController
{
    public const SESSION_ACTION_DATA = 'admin.action.response.content';

    public function __construct(
        private readonly CacheInvalidatorService $cacheInvalidatorService,
        private readonly AdminActionService $adminActionService,
        private readonly RequestStack $requestStack,
        private readonly LoggerInterface $logger,
    ) {}

    #[Route(
        '/update/{name}',
        methods: ['GET'],
        condition: "params['name']
            in [
                'labels',
                'games_and_dex',
                'pokemons',
                'games_availabilities',
                'games_shinies_availabilities',
                'regional_dex_numbers',
            ]"
    )]
    public function update(
        string $name,
    ): Response {
        return $this->execute($name, 'update');
    }

    #[Route(
        '/calculate/{name}',
        methods: ['GET'],
        condition: "params['name']
            in [
                'game_bundles_availabilities',
                'game_bundles_shinies_availabilities',
                'dex_availabilities',
                'pokemon_availabilities',
            ]"
    )]
    public function calculate(
        string $name,
    ): Response {
        return $this->execute($name, 'calculate');
    }

    #[Route(
        '/invalidate/{name}',
        methods: ['GET'],
        condition: "params['name']
            in [
                'labels',
                'dex',
                'albums',
                'reports',
                'actions',
            ]"
    )]
    public function invalidate(
        string $name,
    ): Response {
        return $this->execute($name, 'invalidate');
    }

    private function execute(
        string $name,
        string $action,
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $state = 'ok';
        $content = '';
        $error = '';

        try {
            $this->doAction($name, $action);
        } catch (\Exception $e) {
            $state = 'ko';

            $error = $e->getMessage();

            $this->logger->critical(
                $e->getMessage(),
                [
                    'name' => $name,
                    'action' => $action,
                ]
            );
        }

        $adminAction = new AdminAction(
            $action,
            $name,
            $state,
            $content,
            $error
        );

        $this->requestStack->getSession()->set(self::SESSION_ACTION_DATA, $adminAction);

        return $this->redirectToRoute('app_web_admin_index');
    }

    private function doAction(
        string $name,
        string $action,
    ): void {
        switch ($action) {
            case 'update':
                $this->adminActionService->update($name);

                break;

            case 'calculate':
                $this->adminActionService->calculate($name);

                break;
        }

        $this->cacheInvalidatorService->invalidate($name);
    }
}
