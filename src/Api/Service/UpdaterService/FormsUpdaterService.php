<?php

declare(strict_types=1);

namespace App\Api\Service\UpdaterService;

use App\Api\DTO\DataChangeReport\Report;
use App\Api\Updater\Forms\CategoryFormsUpdater;
use App\Api\Updater\Forms\RegionalFormsUpdater;
use App\Api\Updater\Forms\SpecialFormsUpdater;
use App\Api\Updater\Forms\VariantFormsUpdater;

class FormsUpdaterService extends AbstractUpdaterService
{
    public function __construct(
        private readonly CategoryFormsUpdater $categoryFormsUpdater,
        private readonly RegionalFormsUpdater $regionalFormsUpdater,
        private readonly SpecialFormsUpdater $specialFormsUpdater,
        private readonly VariantFormsUpdater $variantFormsUpdater
    ) {}

    #[\Override]
    public function execute(): void
    {
        $this->categoryFormsUpdater->execute();
        $this->regionalFormsUpdater->execute();
        $this->specialFormsUpdater->execute();
        $this->variantFormsUpdater->execute();

        $this->report = new Report([
            $this->categoryFormsUpdater->getStatistic(),
            $this->regionalFormsUpdater->getStatistic(),
            $this->specialFormsUpdater->getStatistic(),
            $this->variantFormsUpdater->getStatistic(),
        ]);
    }
}
