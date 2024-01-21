<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Commands\Partition;

use Bellangelo\TestSuiteArchitect\Storage\TestSuiteHandler;
use Bellangelo\TestSuiteArchitect\Storage\TimeReportingHandler;
use Bellangelo\TestSuiteArchitect\Partitions\TimeBasedPartitions;
use Minicli\Command\CommandController;
use Throwable;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $numberOfPartitions = (int) $this->getParam('number');

        if ($numberOfPartitions < 2) {
            echo 'Please provide a number of partitions greater than 1.' . PHP_EOL;
            return;
        }

        try {
            $collection = (new TimeReportingHandler())->readReport();

            $partitions = (new TimeBasedPartitions($collection))->createPartitions($numberOfPartitions);
            (new TestSuiteHandler())->writeTestSuites($partitions);

        } catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }
    }

    public function required(): array
    {
        return [
            'number'
        ];
    }
}
