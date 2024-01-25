<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\PHPUnit\Configuration;
use Bellangelo\TestSuiteArchitect\Storage\StorageHandler;
use Bellangelo\TestSuiteArchitect\TimeReporting;
use Exception;
use PHPUnit\Framework\TestSuite;
use ReflectionClass;

abstract class Extension extends TimeReporting
{
    private static int $suitesCounter = 0;
    private static bool $runTimeReport;

    public function __construct()
    {
        parent::__construct();

        self::$runTimeReport = Configuration::shouldRunTimeReport();
    }

    protected function incrementSuitesCounter(): void
    {
        self::$suitesCounter++;
    }

    protected function suiteStarted(TestSuite $suite): void
    {
        if (!$this->shouldRunTimeReport() || !class_exists($suite->getName())) {
            return;
        }

        $this->incrementSuitesCounter();

        $file = $this->extractFilenameFromClass($suite->getName());

        $this->storeStartTime(StorageHandler::getRelativePathBasedOnTests($file));
    }

    protected function suiteEnded(TestSuite $suite): void
    {
        if (!$this->shouldRunTimeReport() || !class_exists($suite->getName())) {
            return;
        }

        $file = $this->extractFilenameFromClass($suite->getName());

        $this->storeEndTime(
            StorageHandler::getRelativePathBasedOnTests($file),
            microtime(true)
        );
    }

    private function extractFilenameFromClass(string $className): string
    {
        if (!class_exists($className)) {
            throw new Exception('Class does not exist');
        }

        $class = new ReflectionClass($className);
        $filename = $class->getFileName();

        if ($filename === false) {
            throw new Exception('Could not extract the filename from the class');
        }

        return $filename;
    }

    private function shouldRunTimeReport(): bool
    {
        return self::$runTimeReport;
    }

    /**
     * Unfortunately, PHPUnit does not provide a way to know when the suites have stopped running.
     */
    public function __destruct()
    {
        if ($this->shouldRunTimeReport()) {
            $this->storeReport();
        }
    }
}
