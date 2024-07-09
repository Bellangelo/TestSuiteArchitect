<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Partitions;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;

abstract class Partitions
{
    private TestTimerCollection $testTimerCollection;

    public function __construct(TestTimerCollection $testTimerCollection) {
        $this->setData($testTimerCollection);
    }

    abstract public function createPartitions(int $numberOfPartitions): array;

    protected function getData(): TestTimerCollection
    {
        return $this->testTimerCollection;
    }

    private function setData(TestTimerCollection $testTimerCollection): void
    {
        $this->testTimerCollection = $testTimerCollection;
    }
}
