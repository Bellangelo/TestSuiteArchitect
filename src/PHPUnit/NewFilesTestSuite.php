<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\PHPUnit;

use Bellangelo\TestSuiteArchitect\Partitions\LoadBalancingPartitions;
use Bellangelo\TestSuiteArchitect\Storage\StorageHandler;
use Bellangelo\TestSuiteArchitect\Storage\TimeReportingHandler;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimer;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;
use Exception;
use PHPUnit\Framework\TestSuite;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use SplFileInfo;

abstract class NewFilesTestSuite extends TestSuite
{
    protected static function addNewTestsForCurrentPartition(
        TestSuite $testSuite,
        int $numberOfPartitions,
        int $index
    ): void {
        $testTimerCollection = self::getNewTestFiles();
        $partitions = (new LoadBalancingPartitions($testTimerCollection))->createPartitions($numberOfPartitions);

        if (isset($partitions[$index])) {
            foreach ($partitions[$index] as $test) {
                try {
                    $testSuite->addTestFile($test->getName());
                } catch (Exception $e) {
                    continue;
                }
            }
        }
    }

    protected static function getNewTestFiles(): TestTimerCollection
    {
        $filesInReport = (new TimeReportingHandler())->readReport();
        $testFiles = self::getTestFiles();
        $testTimerCollection = new TestTimerCollection();

        foreach ($testFiles as $testFile) {
            if (!$filesInReport->get($testFile) instanceof TestTimer) {
                $testTimerCollection->add(
                    new TestTimer(
                        $testFile,
                        0
                    )
                );
            }
        }

        return $testTimerCollection;
    }

    /**
     * @return array<string>
     */
    protected static function getTestFiles(): array
    {
        $testsDirectory = Configuration::getTestsDirectory();

        if (!is_dir($testsDirectory)) {
            throw new RuntimeException('Cannot find tests directory');
        }

        $tests = [];
        $iterator = new RecursiveDirectoryIterator($testsDirectory);

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator($iterator) as $file) {
            // Check if the file ends with 'Test.php'
            if (substr($file->getFilename(), -8) === 'Test.php') {
                // Add to the array
                $tests[] = StorageHandler::getRelativePathBasedOnTests($file->getPathname());
            }
        }

        return $tests;
    }
}
