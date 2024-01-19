<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect;

use Bellangelo\TestSuiteArchitect\Storage\TimeReportingHandler;
use Minicli\App as MinicliApp;
use Minicli\Command\CommandCall;

class App
{
    private MinicliApp $app;

    public function __construct()
    {
        $this->setApp(
            new MinicliApp()
        );

        $this->registerCommands();
    }

    public function runCommand(CommandCall $input): void
    {
        $this->getApp()->runCommand($input->getRawArgs());
    }

    private function setApp(MinicliApp $app): void
    {
        $this->app = $app;
    }

    private function getApp(): MinicliApp
    {
        return $this->app;
    }

    private function registerCommands(): void
    {
        $this->getApp()->registerCommand('partition', function (CommandCall $input) {
            if ($input->getParam('number')) {
                self::createTestSuites((int) $input->getParam('number'));
            } else {
                echo 'Please provide a number of partitions.' . PHP_EOL;
            }
        });
    }

    public static function createTestSuites(int $numberOfPartitions): void
    {
        if ($numberOfPartitions < 2) {
            echo 'Please provide a number of partitions greater than 1.' . PHP_EOL;
            return;
        }

        $data = (new TimeReportingHandler())->readReport();

        $partitions = (new TimeBasedPartitions($data))->createPartitions($numberOfPartitions);

        foreach ($partitions as $partition) {
            echo implode(',', $partition) . PHP_EOL;
        }
    }
}
