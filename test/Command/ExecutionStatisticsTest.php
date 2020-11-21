<?php

namespace CommonUtils\Tests\Command;

use CommonUtils\Command\ExecutionStatistics;
use PHPUnit\Framework\TestCase;

class ExecutionStatisticsTest extends TestCase
{
    private ExecutionStatistics $executionStats;

    protected function setUp(): void
    {
        $this->executionStats = new ExecutionStatistics();
    }

    public function testCalculateStatsSuccess(): void
    {
        $secondsSleep = 0.01;
        $this->executionStats->start();
        usleep($secondsSleep * 1000 * 1000);
        $this->executionStats->end();
        $statsMessage = $this->executionStats->getPrintMessage();

        self::assertSame("\nExecution time: 0.0101 seconds\nMemory peak usage: 6.00 MB\n", $statsMessage);
    }

    public function testCalculateStatsWithoutInitialization(): void
    {
        $statsMessage = $this->executionStats->getPrintMessage();

        self::assertSame("\nExecution time: 0.0000 seconds\nMemory peak usage: 0.00 MB\n", $statsMessage);
    }

    public function testCalculateStatsWithoutFinalization(): void
    {
        $this->executionStats->start();
        $statsMessage = $this->executionStats->getPrintMessage();

        self::assertSame("\nExecution time: 0.0000 seconds\nMemory peak usage: 0.00 MB\n", $statsMessage);
    }
}
