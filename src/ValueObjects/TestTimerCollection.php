<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\ValueObjects;

use Iterator;

class TestTimerCollection implements Iterator
{
    private array $testTimers = [];

    public function add(TestTimer $testTimer): void
    {
        $this->testTimers[$testTimer->getName()] = $testTimer;
    }

    public function get(string $name): ?TestTimer
    {
        return $this->testTimers[$name] ?? null;
    }

    public function current(): TestTimer
    {
        return current($this->testTimers);
    }

    public function next(): ?TestTimer
    {
        $value = next($this->testTimers);
        return $value ?: null;
    }

    public function key(): ?string
    {
        return key($this->testTimers);
    }

    public function valid(): bool
    {
        return isset($this->testTimers[$this->key()]);
    }

    public function rewind(): void
    {
        reset($this->testTimers);
    }

    public function toArray(): array
    {
        return $this->testTimers;
    }
}
