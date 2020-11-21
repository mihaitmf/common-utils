<?php

namespace CommonUtils\Command;

class ExecutionStatistics
{
    private const PRINT_FORMAT = "\nExecution time: %.4f seconds\nMemory peak usage: %.2f MB\n";

    private float $startTime;
    private float $executionTimeSeconds;
    private float $memoryMegabytesUsed;

    public static function printStats(float $startTime): void
    {
        print(sprintf(
            self::PRINT_FORMAT,
            self::getExecutionTimeSeconds($startTime),
            self::getMemoryMegabytesUsed()
        ));
    }

    public function start(): void
    {
        $this->startTime = microtime(true);
    }

    public function end(): void
    {
        $this->executionTimeSeconds = self::getExecutionTimeSeconds($this->startTime);
        $this->memoryMegabytesUsed = self::getMemoryMegabytesUsed();
    }

    public function getPrintMessage(): string
    {
        return sprintf(
            self::PRINT_FORMAT,
            $this->executionTimeSeconds,
            $this->memoryMegabytesUsed
        );
    }

    private static function getExecutionTimeSeconds(float $startTime): float
    {
        return microtime(true) - $startTime;
    }

    private static function getMemoryMegabytesUsed(): float
    {
        return memory_get_peak_usage(true) / 1024 / 1024;
    }
}
