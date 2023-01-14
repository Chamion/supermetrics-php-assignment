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
    private $posts = [];

    private function calculateMonthlyTimeranges(): array
    {
        $dateCursor = $this->parameters->getStartDate();
        $end = $this->parameters->getEndDate();
        $months = [];
        while ($dateCursor->format('F, Y') !== $end->format('F, Y')) {
            array_push($months, clone $dateCursor);
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
        array_push($this->posts, $postTo);
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $stats = new StatisticsTo();
        foreach ($this->calculateMonthlyTimeranges() as $month) {
            $child = (new StatisticsTo())
                ->setSplitPeriod($month->format('F, Y'))
                ->setValue(
                    count(array_filter($this->posts, fn ($post) => $post->getDate()->format('F, Y') === $month->format('F, Y')))
                );
            $stats->addChild($child);
        }

        return $stats;
    }
}
