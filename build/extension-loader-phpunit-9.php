<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;

class ExtensionLoader extends Extension implements TestListener
{
    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $testSuite): void
    {
        $this->suiteStarted($testSuite);
    }

    public function endTestSuite(TestSuite $testSuite): void
    {
        $this->suiteEnded($testSuite);
    }
}
