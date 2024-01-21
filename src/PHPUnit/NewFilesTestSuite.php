<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect;

use Bellangelo\TestSuiteArchitect\Storage\StorageHandler;
use Bellangelo\TestSuiteArchitect\Storage\TimeReportingHandler;
use http\Exception\RuntimeException;
use PHPUnit\Framework\TestSuite;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class NewFilesTestSuite extends TestSuite
{
    protected static function findNewFiles(): array
    {
        $filesInReport = (new TimeReportingHandler())->readReport();
        $testFiles = self::getTestFiles();
        $newFiles = [];

        foreach ($testFiles as $file) {
            if (!$filesInReport->get($file)) {
                $newFiles[] = $file;
            }
        }

        return $newFiles;
    }

    protected static function getTestFiles(): array
    {
        $testsDirectory = StorageHandler::getAbsoluteFolder('tests');

        if (is_dir($testsDirectory)) {
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
