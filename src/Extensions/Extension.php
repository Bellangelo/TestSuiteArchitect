<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\PHPUnit\Configuration;
use Bellangelo\TestSuiteArchitect\TimeReporting;
use PHPUnit\Framework\TestSuite;
use ReflectionClass;
use RuntimeException;

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

        $this->storeStartTime($this->extractFilenameFromClass($suite->getName()));
    }

    protected function suiteEnded(TestSuite $suite): void
    {
        if (!class_exists($suite->getName())) {
            return;
        }

        $this->storeEndTime(
            $this->extractFilenameFromClass($suite->getName()),
            microtime(true)
        );

        $this->storeReport();
    }

    private function extractFilenameFromClass(string $className): string
    {
        $workingDirectory = Configuration::getWorkingDirectory();

        $class = new ReflectionClass($className);
        $filename = $class->getFileName();

        if (strpos($filename, $workingDirectory) === false) {
            throw new RuntimeException('Test exists in a unexpected directory');
        }

        return substr($filename, strlen($workingDirectory));
    }
}
