<?php

declare(strict_types=1);

namespace App\Web\Service\Api;

use App\Web\DTO\ActionLog;
use App\Web\DTO\ActionLogData;
use App\Web\Utils\JsonDecoder;

class GetActionLogsService extends AbstractApiService
{
    /**
     * @return array<string, ActionLogData>
     */
    public function get(): array
    {
        $json = $this->requestContent(
            'GET',
            "/action_logs"
        );

        /** @var string[][]|int[][]|int[][][] */
        $actionLogsData = JsonDecoder::decode($json);

        $list = [];
        foreach ($actionLogsData as $item => $data) {
            /** @var string[]|int[]|int[][] */
            $currentData = $data['current'];
            /** @var string[]|int[]|int[][] */
            $lastData = $data['last'] ?? null;

            $list[$item] = new ActionLogData(
                $item,
                ActionLog::createFromArray($currentData),
                $lastData ? ActionLog::createFromArray($lastData) : null,
            );
        }

        return $list;
    }
}
