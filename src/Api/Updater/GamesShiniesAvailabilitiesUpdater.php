<?php

declare(strict_types=1);

namespace App\Api\Updater;

final class GamesShiniesAvailabilitiesUpdater extends GamesAvailabilitiesUpdater
{
    protected string $sheetName = 'Games Shinies Availability';
    protected string $tableName = 'game_shiny_availability';
    protected string $statisticName = 'games_shinies_availabilities';
}
