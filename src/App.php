<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect;

use Bellangelo\TestSuiteArchitect\Adapters\PartitionsAdapter;
use Bellangelo\TestSuiteArchitect\Partitions\TimeBasedPartitions;
use Minicli\App as MinicliApp;
use Minicli\Command\CommandCall;

class App
{
    private static string $partitionAdapterClass = TimeBasedPartitions::class;

    private MinicliApp $minicliApp;

    public function __construct()
    {
        $this->initApp();
    }

    public function runCommand(CommandCall $commandCall): void
    {
        $this->minicliApp->runCommand($commandCall->getRawArgs());
    }

    public static function getPartitionAdapter(): PartitionsAdapter
    {
        return new PartitionsAdapter(self::getPartitionAdapterClass());
    }

    private static function getPartitionAdapterClass(): string
    {
        return self::$partitionAdapterClass;
    }

    private function setApp(MinicliApp $minicliApp): void
    {
        $this->minicliApp = $minicliApp;
    }

    private function initApp(): void
    {
        $minicliApp = new MinicliApp(
            [
                'app_path' => __DIR__ . '/Commands/',
            ],
            './testsuitearchitect'
        );

        $this->setApp($minicliApp);
    }
}
