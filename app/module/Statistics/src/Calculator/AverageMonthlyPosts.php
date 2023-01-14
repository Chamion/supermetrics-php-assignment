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
        return new StatisticsTo();
    }
}
