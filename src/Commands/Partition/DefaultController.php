<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Commands\Partition;

use Bellangelo\TestSuiteArchitect\App;
use Bellangelo\TestSuiteArchitect\Storage\TestSuiteHandler;
use Bellangelo\TestSuiteArchitect\Storage\TimeReportingHandler;
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

            $partitions = App::getPartitionAdapter()->createPartitions($collection, $numberOfPartitions);
            (new TestSuiteHandler())->writeTestSuites($partitions);

        } catch (Throwable $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }
    }

    /**
     * @return array<string>
     */
    public function required(): array
    {
        return [
            'number'
        ];
    }
}
