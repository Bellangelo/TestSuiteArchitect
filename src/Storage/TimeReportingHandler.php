<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Storage;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimer;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;
use RuntimeException;

class TimeReportingHandler extends StorageHandler
{
    private const FILE_NAME = 'time-reporting.csv';

    public function writeReport(TestTimerCollection $collection): void
    {
        $file = fopen($this->getAbsolutePath(self::FILE_NAME), 'w');

        if (!$file) {
            throw new RuntimeException('Unable to write report');
        }

        foreach ($collection as $testTimer) {
            fputcsv($file, $testTimer->toCsvArray());
        }

        fclose($file);
    }

    public function readReport(): TestTimerCollection
    {
        $file = fopen($this->getAbsolutePath(self::FILE_NAME), 'r');

        if (!$file) {
            throw new RuntimeException('Please generate a report first.');
        }

        $collection = new TestTimerCollection();

        while (($line = fgetcsv($file)) !== false) {
            if (!isset($line[0], $line[1], $line[2])) {
                throw new RuntimeException('Invalid report file.');
            }

            $collection->add(
                new TestTimer(
                    $line[0],
                    (float) $line[1],
                    (float) $line[2]
                )
            );
        }

        fclose($file);

        return $collection;
    }
}
