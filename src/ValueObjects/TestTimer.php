<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\ValueObjects;

use InvalidArgumentException;

class TestTimer
{
    private string $name;

    private float $startTime;

    private float $endTime;

    public function __construct(string $name, float $startTime, float $endTime = 0.0)
    {
        $this->setName($name);
        $this->setStartTime($startTime);
        $this->setEndTime($endTime);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStartTime(): float
    {
        return $this->startTime;
    }

    public function getEndTime(): float
    {
        return $this->endTime;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEndTime(float $endTime): void
    {
        if ($endTime !== 0.0 && $endTime < $this->getStartTime()) {
            throw new InvalidArgumentException('End time cannot be less than start time');
        }

        $this->endTime = $endTime;
    }

    public function setStartTime(float $startTime): void
    {
        if ($startTime < 0) {
            throw new InvalidArgumentException('Start time cannot be negative');
        }

        $this->startTime = $startTime;
    }

    public function getElapsedTime(): float
    {
        if ($this->getEndTime() === 0.0) {
            return 0;
        }

        return $this->getEndTime() - $this->getStartTime();
    }

    public function toCsvArray(): array
    {
        return [
            $this->getName(),
            $this->getStartTime(),
            $this->getEndTime(),
        ];
    }
}
