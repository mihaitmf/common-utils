<?php

namespace CommonUtils\Tests\Command;

use CommonUtils\Command\ConsoleEventListener;
use CommonUtils\Command\ExecutionStatistics;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleEventListenerTest extends TestCase
{
    private ConsoleEventListener $consoleEventListener;

    /** @var ExecutionStatistics|MockObject */
    private $executionStatisticsMock;
    /** @var MockObject|OutputInterface */
    private $outputInterfaceMock;

    protected function setUp(): void
    {
        $this->executionStatisticsMock = $this->createMock(ExecutionStatistics::class);
        $this->outputInterfaceMock = $this->createMock(OutputInterface::class);

        $this->consoleEventListener = new ConsoleEventListener(
            $this->executionStatisticsMock,
            $this->outputInterfaceMock
        );
    }

    public function testPrintLogsOnCommandBegin(): void
    {
        $commandName = 'my test command';
        $commandStub = $this->getCommandStub($commandName);

        $event = new ConsoleCommandEvent(
            $commandStub,
            $this->createStub(InputInterface::class),
            $this->createStub(OutputInterface::class)
        );

        $this->executionStatisticsMock->expects(self::once())->method('start')
            ->with();

        $expectedMessage = sprintf(
            "[%s] Command %s started...\n",
            date('Y-m-d'),
            $commandName
        );

        $this->outputInterfaceMock->expects(self::once())->method('writeln')
            ->with(self::callback(function ($message) use ($expectedMessage) {
                $messageWithoutTime = substr_replace(
                    $message,
                    '',
                    strlen('[YYYY-mm-dd'),
                    strlen(' HH:ii:ss')
                );

                self::assertSame($expectedMessage, $messageWithoutTime, 'The printed message is different than expected');

                return true;
            }));

        $this->consoleEventListener->onCommandBegin($event);
    }

    public function testPrintLogsOnCommandFinish(): void
    {
        $commandName = 'my test command';
        $exitCode = 0;
        $commandStub = $this->getCommandStub($commandName);
        $event = new ConsoleTerminateEvent(
            $commandStub,
            $this->createStub(InputInterface::class),
            $this->createStub(OutputInterface::class),
            $exitCode
        );

        $executionStatsMessage = 'exec stats message';
        $this->executionStatisticsMock->expects(self::once())->method('end')
            ->with();
        $this->executionStatisticsMock->expects(self::once())->method('getPrintMessage')
            ->with()
            ->willReturn($executionStatsMessage);

        $expectedFinishMessage = sprintf(
            "\n[%s] Command %s finished with exit code %s.",
            date('Y-m-d'),
            $commandName,
            $exitCode
        );

        $this->outputInterfaceMock->expects(self::exactly(2))->method('writeln')
            ->withConsecutive(
                [$executionStatsMessage],
                [self::callback(function ($message) use ($expectedFinishMessage) {
                    $messageWithoutTime = substr_replace(
                        $message,
                        '',
                        strlen("\n[YYYY-mm-dd"),
                        strlen(' HH:ii:ss')
                    );

                    self::assertSame($expectedFinishMessage, $messageWithoutTime, 'The printed message is different than expected');

                    return true;
                })],
            );

        $this->consoleEventListener->onCommandFinish($event);
    }

    public function testPrintLogsOnCommandBeginAndFinish(): void
    {
        $consoleEventListener = new ConsoleEventListener(
            new ExecutionStatistics(),
            new ConsoleOutput()
        );

        $commandName = 'my test command';
        $exitCode = 0;

        $startEvent = new ConsoleCommandEvent(
            new Command($commandName),
            new ArgvInput(),
            new NullOutput()
        );
        $finishEvent = new ConsoleTerminateEvent(
            new Command($commandName),
            new ArgvInput(),
            new NullOutput(),
            $exitCode
        );

        ob_start();
        $consoleEventListener->onCommandBegin($startEvent);
        $consoleEventListener->onCommandFinish($finishEvent);
        $actualOutput = ob_get_clean();

        $expectedOutput = sprintf(
            "[%s] Command %s started...\n\nExecution time: %.4f seconds\nMemory peak usage: %.2f MB\n\n[%s] Command %s finished with exit code %s.",
            date('Y-m-d H:i:s'),
            $commandName,
            0.0001,
            6.00,
            date('Y-m-d H:i:s'),
            $commandName,
            $exitCode
        );

        self::assertSame($expectedOutput, $actualOutput, 'Printed output is different than expected');
    }

    /**
     * @param string $commandName
     * @return Stub|Command
     */
    private function getCommandStub(string $commandName)
    {
        $commandStub = $this->createStub(Command::class);
        $commandStub->method('getName')->willReturn($commandName);

        return $commandStub;
    }
}
