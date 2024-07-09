<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Adapters;

use Bellangelo\TestSuiteArchitect\Partitions\LoadBalancingPartitions;
use Bellangelo\TestSuiteArchitect\Partitions\Partitions;
use Bellangelo\TestSuiteArchitect\Partitions\TimeBasedPartitions;
use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimerCollection;
use InvalidArgumentException;

class PartitionsAdapter
{
    private const AVAILABLE_ADAPTERS = [
        LoadBalancingPartitions::class,
        TimeBasedPartitions::class
    ];

    private const DEFAULT_ADAPTER = TimeBasedPartitions::class;

    private string $adapterClass;

    public function __construct(?string $adapter = null)
    {
        $adapter ??= self::DEFAULT_ADAPTER;

        if (!in_array($adapter, self::AVAILABLE_ADAPTERS)) {
            throw new InvalidArgumentException('Invalid adapter');
        }

        $this->setAdapterClass($adapter);
    }

    private function setAdapterClass(string $adapter): void
    {
        $this->adapterClass = $adapter;
    }

    public function getAdapter(TestTimerCollection $testTimerCollection): Partitions
    {
        /** @phpstan-ignore-next-line */
        return new ($this->adapterClass)($testTimerCollection);
    }

    public function createPartitions(TestTimerCollection $testTimerCollection, int $numberOfPartitions): array
    {
        return $this->getAdapter($testTimerCollection)->createPartitions($numberOfPartitions);
    }
}
