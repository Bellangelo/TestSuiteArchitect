<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect;

abstract class Partitions
{
    private array $data = [];

    public function __construct(array $data) {
        $this->setData($data);
    }

    abstract public function createPartitions(int $numberOfPartitions): array;

    protected function getData(): array
    {
        return $this->data;
    }

    private function setData(array $data): void
    {
        $this->data = $data;
    }
}
