<?php

declare(strict_types=1);

namespace App\Tests\Web\Browser\Album;

use App\Tests\Web\Common\Traits\TestNavTrait;
use App\Tests\Web\Browser\AbstractBrowserTestCase;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @group browser-testing
 */
class LoadingTimeTest extends AbstractBrowserTestCase
{
    use TestNavTrait;

    public function testLoadingTime(): void
    {
        $client = $this->getNewClient();

        $stopwatch = new Stopwatch();
        $stopwatch->start('request');

        $client->request('GET', '/fr/album/demo?t=f86cbe805674d85f7806b175b70647a6a9334631');

        $event = $stopwatch->stop('request');

        $this->assertLessThanOrEqual(
            7 * 1000,
            $event->getDuration()
        );
    }
}
