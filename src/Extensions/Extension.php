<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\Storage\StorageHandler;
use Bellangelo\TestSuiteArchitect\TimeReporting;
use PHPUnit\Framework\TestSuite;
use ReflectionClass;

abstract class Extension extends TimeReporting
{
    private static int $suitesCounter = 0;

    protected function incrementSuitesCounter(): void
    {
        self::$suitesCounter++;
    }

    protected function suiteStarted(TestSuite $suite): void
    {
        if (!class_exists($suite->getName())) {
            return;
        }

        $this->incrementSuitesCounter();

        $file = $this->extractFilenameFromClass($suite->getName());

        $this->storeStartTime(StorageHandler::convertToRelativePath($file));
    }

    protected function suiteEnded(TestSuite $suite): void
    {
        if (!class_exists($suite->getName())) {
            return;
        }

        $file = $this->extractFilenameFromClass($suite->getName());

        $this->storeEndTime(
            StorageHandler::convertToRelativePath($file),
            microtime(true)
        );

        $this->storeReport();
    }

    private function extractFilenameFromClass(string $className): string
    {
        $class = new ReflectionClass($className);
        return $class->getFileName();
    }
}
