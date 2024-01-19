<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use Bellangelo\TestSuiteArchitect\TimeReporting;

abstract class Extension extends TimeReporting
{
    private static int $suitesCounter = 0;

    protected function incrementSuitesCounter(): void
    {
        self::$suitesCounter++;
    }

    protected function isLastSuite(int $totalSuites): bool
    {
        return self::$suitesCounter === $totalSuites;
    }
}
