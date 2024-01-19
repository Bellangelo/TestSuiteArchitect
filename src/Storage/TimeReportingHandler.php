<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Storage;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;

class TimeReportingHandler extends StorageHandler
{
    private const FILE_NAME = 'time-reporting.csv';

    public function writeReport(TestTimerCollection $collection): void
    {
        $file = fopen($this->getAbsolutePath(self::FILE_NAME), 'w');

        foreach ($collection as $testTimer) {
            fputcsv($file, $testTimer->toCsvArray());
        }

        fclose($file);
    }
}
