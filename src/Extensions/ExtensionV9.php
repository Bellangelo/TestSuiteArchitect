<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class ExtensionV9 extends Extension implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $suite): void
    {
        $this->storeStartTime($suite->getName());
    }

    public function endTestSuite(TestSuite $suite): void
    {
        $this->incrementSuitesCounter();

        $this->storeEndTime($suite->getName(), microtime(true));

        $this->storeReport();
    }
}
