<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimer;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;
use PHPUnit\Runner\AfterTestHook;
use PHPUnit\Runner\BeforeTestHook;
use RuntimeException;

class TimeReportingTestListener implements BeforeTestHook, AfterTestHook
{
    private TestTimerCollection $testTimerCollection;

    public function __construct()
    {
        $this->testTimerCollection = new TestTimerCollection();
    }

    public function executeBeforeTest(string $test): void
    {
        $this->storeTest($test);
    }

    public function executeAfterTest(string $test, float $time): void
    {
        $testTimer = $this->getTestTimerCollection()->get($test);

        if (is_null($testTimer)) {
            throw new RuntimeException('Test timer not found');
        }

        $testTimer->setEndTime(microtime(true));
    }

    private function getTestTimerCollection(): TestTimerCollection
    {
        return $this->testTimerCollection;
    }

    private function storeTest(string $test): void
    {
        $this->getTestTimerCollection()->add(
            new TestTimer(
                $test,
                microtime(true)
            )
        );
    }
}
