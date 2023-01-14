<?php

declare(strict_types=1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 * Class AverageMonthlyPosts
 *
 * @package Statistics\Calculator
 */
class AverageMonthlyPosts extends AbstractCalculator
{
    private function calculateMonthsInParamsRange(): array
    {
        $dateCursor = $this->parameters->getStartDate();
        $end = $this->parameters->getEndDate()->format('F, Y');
        $months = [];
        while ($dateCursor->format('F, Y') !== $end) {
            array_push($months, $dateCursor->format('F, Y'));
            $dateCursor->modify('+1 month');
        }
        array_push($months, $end);
        return $months;
    }

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {


        $stats = new StatisticsTo();
        foreach ($this->calculateMonthsInParamsRange() as $month) {
            $stats->addChild(new StatisticsTo());
        }

        return $stats;
    }
}
