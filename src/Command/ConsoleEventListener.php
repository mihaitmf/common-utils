<?php

namespace CommonUtils\Command;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleEventListener
{
    private ExecutionStatistics $executionStatistics;
    private OutputInterface $output;

    public function __construct(ExecutionStatistics $executionStatistics, OutputInterface $output)
    {
        $this->executionStatistics = $executionStatistics;
        $this->output = $output;
    }

    public function onCommandBegin(ConsoleCommandEvent $event): void
    {
        $this->executionStatistics->start();
        $this->output->writeln(sprintf(
            "[%s] Command %s started...\n",
            date('Y-m-d H:i:s'),
            $event->getCommand()->getName()
        ));
    }

    public function onCommandFinish(ConsoleTerminateEvent $event): void
    {
        $this->executionStatistics->end();
        $this->output->writeln($this->executionStatistics->getPrintMessage());
        $this->output->writeln(sprintf(
            "\n[%s] Command %s finished with exit code %s.",
            date('Y-m-d H:i:s'),
            $event->getCommand()->getName(),
            $event->getExitCode()
        ));
    }
}
