<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\TimeReporting;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\Warning;
use PHPUnit\Runner\BeforeTestHook;
use Throwable;

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
}
