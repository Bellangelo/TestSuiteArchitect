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
        $this->getParam('number');
        echo 'Please provide a number of partitions greater than 1.' . PHP_EOL;
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
