<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\PHPUnit;

use Bellangelo\TestSuiteArchitect\Partitions\LoadBalancingPartitions;
use Bellangelo\TestSuiteArchitect\Storage\StorageHandler;
use Bellangelo\TestSuiteArchitect\Storage\TimeReportingHandler;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimer;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;
use http\Exception\RuntimeException;
use PHPUnit\Framework\TestSuite;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

abstract class NewFilesTestSuite extends TestSuite
{
    protected static function addNewTestsForCurrentPartition(
        TestSuite $suite,
        int $numberOfPartitions,
        int $index
    ): void {
        $newTests = self::getNewTestFiles();
        $partitions = (new LoadBalancingPartitions($newTests))->createPartitions($numberOfPartitions);

        if (isset($partitions[$index])) {
            foreach ($partitions[$index] as $test) {
                $suite->addTestFile($test->getName());
            }
        }
    }

    protected static function getNewTestFiles(): TestTimerCollection
    {
        $filesInReport = (new TimeReportingHandler())->readReport();
        $testFiles = self::getTestFiles();
        $newFiles = new TestTimerCollection();

        foreach ($testFiles as $file) {
            if (!$filesInReport->get($file)) {
                $newFiles->add(
                    new TestTimer(
                        $file->getPathname(),
                        0
                    )
                );
            }
        }

        return $newFiles;
    }

    protected static function getTestFiles(): array
    {
        $testsDirectory = StorageHandler::getAbsoluteFolder('tests');

        if (!is_dir($testsDirectory)) {
            throw new RuntimeException('Cannot find tests directory');
        }

        $tests = [];
        $iterator = new RecursiveDirectoryIterator($testsDirectory);

        foreach (new RecursiveIteratorIterator($iterator) as $file) {
            // Check if the file ends with 'Test.php'
            if (substr($file->getFilename(), -8) === 'Test.php') {
                // Add to the array
                $tests[] = $file->getPathname();
            }
        }

        return $tests;
    }
}
