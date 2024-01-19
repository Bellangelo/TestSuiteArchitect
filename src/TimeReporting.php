<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect;

use Bellangelo\TestSuiteArchitect\Storage\TimeReportingHandler;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimer;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;
use RuntimeException;

class TimeReporting
{
    private TestTimerCollection $testTimerCollection;

    public function __construct()
    {
        $this->testTimerCollection = new TestTimerCollection();
    }

    public function storeStartTime(string $test): void
    {
        $this->storeTest($test);
    }

    public function storeEndTime(string $test, float $time): void
    {
        $testTimer = $this->getTestTimerCollection()->get($test);

        if (is_null($testTimer)) {
            throw new RuntimeException('Test timer not found');
        }

        $testTimer->setEndTime(microtime(true));
    }

    public function storeReport(): void
    {
        (new TimeReportingHandler())->writeReport($this->getTestTimerCollection());
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
