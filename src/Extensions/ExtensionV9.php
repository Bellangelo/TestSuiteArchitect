<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\TimeReporting;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class ExtensionV9 extends TimeReporting implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTest(Test $test): void
    {
        $this->storeStartTime($test->getName());
    }

    public function endTest(Test $test, float $time): void
    {
        $this->storeEndTime($test->getName(), $time);
    }

    public function endTestSuite(TestSuite $suite): void
    {
        $this->storeReport();
    }
}
