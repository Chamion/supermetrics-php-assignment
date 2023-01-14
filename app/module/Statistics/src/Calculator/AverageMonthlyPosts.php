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

    private function calculateMonths(): array
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

    private function countUsers(string $month)
    {
        $uniqueIds = [];
        foreach ($this->posts[$month] ?? [] as $post) $uniqueIds[$post->getAuthorId()] = true;
        return count($uniqueIds);
    }

    private function averageUserPosts(string $month)
    {
        $usersAmount = $this->countUsers($month);
        if ($usersAmount === 0) {
            return 0;
        } else {
            return count($this->posts[$month] ?? []) / $this->countUsers($month);
        }
    }

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        if ($postTo->getDate() === null || $postTo->getAuthorId() === null) return;
        $month = $postTo->getDate()->format('F, Y');
        if (!array_key_exists($month, $this->posts)) $this->posts[$month] = [];
        array_push($this->posts[$month], $postTo);
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $stats = new StatisticsTo();
        foreach ($this->calculateMonths() as $month) {
            $child = (new StatisticsTo())
                ->setSplitPeriod($month)
                ->setValue(
                    $this->averageUserPosts($month)
                );
            $stats->addChild($child);
        }

        return $stats;
    }
}
