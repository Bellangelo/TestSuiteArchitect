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

    private MinicliApp $app;

    public function __construct()
    {
        $this->initApp();
    }

    public function runCommand(CommandCall $input): void
    {
        $this->getApp()->runCommand($input->getRawArgs());
    }

    public static function getPartitionAdapter(): PartitionsAdapter
    {
        return new PartitionsAdapter(self::getPartitionAdapterClass());
    }

    private static function getPartitionAdapterClass(): string
    {
        return self::$partitionAdapterClass;
    }

    private function setApp(MinicliApp $app): void
    {
        $this->app = $app;
    }

    private function getApp(): MinicliApp
    {
        return $this->app;
    }

    private function initApp(): void
    {
        $app = new MinicliApp(
            [
                'app_path' => __DIR__ . '/Commands/',
            ],
            './testsuitearchitect'
        );

        $this->setApp($app);
    }
}
