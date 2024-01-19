<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\TimeReporting;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeTestHook;

class ExtensionV10 extends TimeReporting implements BeforeTestHook, AfterTestHook, AfterLastTestHook
{
    public function executeBeforeTest(string $test): void
    {
        $this->storeStartTime($test);
    }

    public function executeAfterTest(string $test, float $time): void
    {
        $this->storeEndTime($test, $time);
    }

    public function executeAfterLastTest(): void
    {
        $this->storeReport();
    }
}
