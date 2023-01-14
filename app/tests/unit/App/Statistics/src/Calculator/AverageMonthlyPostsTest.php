<?php

declare(strict_types=1);

namespace Tests\unit;

use DateTime;
use PHPUnit\Framework\TestCase;
use Statistics\Builder\ParamsBuilder;
use Statistics\Calculator\AverageMonthlyPosts;
use Statistics\Calculator\Factory\StatisticsCalculatorFactory;
use Statistics\Dto\ParamsTo;
use Statistics\Enum\StatsEnum;

/**
 * Class AverageMonthlyPostsTest
 *
 * @package Tests\App/Statistics/src/Calculator
 */
class AverageMonthlyPostsTest extends TestCase
{
    public function testCalculatesChildForEachMonth(): void
    {
        $start = DateTime::createFromFormat('F, Y', 'December, 2022')->modify('first day of this month')->setTime(0, 0, 0);
        $end = DateTime::createFromFormat('F, Y', 'January, 2023')->modify('last day of this month')->setTime(23, 59, 59);
        $params = (new ParamsTo())
            ->setStatName(StatsEnum::AVERAGE_POSTS_NUMBER_PER_USER_PER_MONTH)
            ->setStartDate($start)
            ->setEndDate($end);
        $calculator = new AverageMonthlyPosts();
        $calculator->setParameters($params);
        $result = $calculator->calculate();
        $this->assertEquals(
            2,
            count($result->getChildren())
        );
    }
}
