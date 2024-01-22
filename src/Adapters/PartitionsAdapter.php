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
        $adapter = $adapter ?? self::DEFAULT_ADAPTER;

        if (!in_array($adapter, self::AVAILABLE_ADAPTERS)) {
            throw new InvalidArgumentException('Invalid adapter');
        }

        $this->setAdapterClass($adapter);
    }

    private function setAdapterClass(string $adapter): void
    {
        $this->adapterClass = $adapter;
    }

    private function getAdapterClass(): string
    {
        return $this->adapterClass;
    }

    public function getAdapter(TestTimerCollection $data): Partitions
    {
        /** @phpstan-ignore-next-line */
        return new ($this->getAdapterClass())($data);
    }

    public function createPartitions(TestTimerCollection $data, int $numberOfPartitions): array
    {
        return $this->getAdapter($data)->createPartitions($numberOfPartitions);
    }
}
