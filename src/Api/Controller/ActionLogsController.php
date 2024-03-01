<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Api\Repository\ActionLogsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/action_logs')]
class ActionLogsController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route(path: '', methods: ['GET'])]
    public function get(ActionLogsRepository $repo): JsonResponse
    {
        $actionLogs = $repo->getLastests();

        array_walk(
            $actionLogs,
            function (&$actionLog) {
                if (null !== $actionLog['details']) {
                    $actionLog['details'] = json_decode($actionLog['details'], true);
                }

                if (null !== $actionLog['execution_time'] && is_string($actionLog['execution_time'])) {
                    list($actionLog['execution_time'], $zero) = explode('.', $actionLog['execution_time']);
                    unset($zero);
                }
            }
        );

        $finalActionLogs = [];
        foreach ($actionLogs as $actionLog) {
            $typeAction = $actionLog['type_action'];
            $period = $actionLog['row_number'] == 1 ? 'current' : 'last';

            $finalActionLogs[$typeAction][$period] = $actionLog;
        }

        return new JsonResponse($finalActionLogs);
    }
}
