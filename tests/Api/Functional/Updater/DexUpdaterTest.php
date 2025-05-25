<?php

declare(strict_types=1);

namespace App\Tests\Api\Functional\Updater;

use App\Api\Updater\AbstractUpdater;
use App\Api\Updater\DexUpdater;
use App\Tests\Api\Common\Traits\GetterTrait\GetDexTrait;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(DexUpdater::class)]
#[CoversClass(AbstractUpdater::class)]
class DexUpdaterTest extends AbstractTestUpdater
{
    use GetDexTrait;

    protected int $initialTotalCount = 9;
    protected int $finalTotalCount = 25;
    protected int $initialDeletedTotalCount = 1;
    protected int $mustBeDeletedTotalCount = 4;
    protected string $sheetName = 'Dex';
    protected string $tableName = 'dex';

    public function testDexRegion(): void
    {
        $redGreenBlueYellowBefore = $this->getDexFromSlug('redgreenblueyellow');
        $rubySapphireEmeraldBefore = $this->getDexFromSlug('rubysapphireemerald');

        $this->assertEquals('Kanto', $redGreenBlueYellowBefore['region_name']);
        $this->assertNull($rubySapphireEmeraldBefore['region_name']);

        $this->getService()->execute('Dex');

        $redGreenBlueYellowAfter = $this->getDexFromSlug('redgreenblueyellow');
        $rubySapphireEmeraldAfter = $this->getDexFromSlug('rubysapphireemerald');

        $this->assertEquals('Kanto', $redGreenBlueYellowAfter['region_name']);
        $this->assertEquals('Hoenn', $rubySapphireEmeraldAfter['region_name']);
    }

    public function testDexIsReleased(): void
    {
        $redGreenBlueYellowBefore = $this->getDexFromSlug('redgreenblueyellow');
        $rubySapphireEmeraldBefore = $this->getDexFromSlug('rubysapphireemerald');

        $this->assertTrue($redGreenBlueYellowBefore['is_released']);
        $this->assertTrue($rubySapphireEmeraldBefore['is_released']);

        $this->getService()->execute('Dex');

        $redGreenBlueYellowAfter = $this->getDexFromSlug('redgreenblueyellow');
        $rubySapphireEmeraldAfter = $this->getDexFromSlug('rubysapphireemerald');

        $this->assertFalse($redGreenBlueYellowAfter['is_released']);
        $this->assertTrue($rubySapphireEmeraldAfter['is_released']);
    }

    #[\Override]
    protected function getService(): AbstractUpdater
    {
        /** @var DexUpdater */
        return static::getContainer()->get(DexUpdater::class);
    }
}
