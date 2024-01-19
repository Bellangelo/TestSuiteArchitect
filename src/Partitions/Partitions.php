<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Partitions;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;

abstract class Partitions
{
    private TestTimerCollection $data;

    public function __construct(TestTimerCollection $data) {
        $this->setData($data);
    }

    abstract public function createPartitions(int $numberOfPartitions): array;

    protected function getData(): TestTimerCollection
    {
        return $this->data;
    }

    private function setData(TestTimerCollection $data): void
    {
        $this->data = $data;
    }
}
