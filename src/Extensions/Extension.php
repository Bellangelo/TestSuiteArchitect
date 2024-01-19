<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\TimeReporting;
use PHPUnit\Framework\TestSuite;

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

        $this->storeStartTime($suite->getName());
    }

    protected function suiteEnded(TestSuite $suite): void
    {
        if (!class_exists($suite->getName())) {
            return;
        }

        $this->storeEndTime($suite->getName(), microtime(true));

        $this->storeReport();
    }
}
