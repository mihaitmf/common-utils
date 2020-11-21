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

    public function testCalculateStatsSuccessUsingStartEnd(): void
    {
        $secondsSleep = 0.01;
        $this->executionStats->start();
        usleep($secondsSleep * 1000 * 1000);
        $this->executionStats->end();
        $statsMessage = $this->executionStats->getPrintMessage();

        self::assertSame("\nExecution time: 0.0101 seconds\nMemory peak usage: 6.00 MB\n", $statsMessage);
    }

    public function testCalculateStatsSuccessUsingStaticMethod(): void
    {
        $secondsSleep = 0.005;
        ob_start();

        $startTime = microtime(true);
        usleep($secondsSleep * 1000 * 1000);
        ExecutionStatistics::printStats($startTime);

        $statsMessage = ob_get_clean();

        self::assertSame("\nExecution time: 0.0051 seconds\nMemory peak usage: 6.00 MB\n", $statsMessage);
    }

    public function testCalculateStatsFailWithoutInitialization(): void
    {
        $statsMessage = $this->executionStats->getPrintMessage();

        self::assertSame("\nExecution time: 0.0000 seconds\nMemory peak usage: 0.00 MB\n", $statsMessage);
    }

    public function testCalculateStatsFailWithoutFinalization(): void
    {
        $this->executionStats->start();
        $statsMessage = $this->executionStats->getPrintMessage();

        self::assertSame("\nExecution time: 0.0000 seconds\nMemory peak usage: 0.00 MB\n", $statsMessage);
    }
}
