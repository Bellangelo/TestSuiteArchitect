<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Extensions;

use PHPUnit\Event\Facade;
use PHPUnit\Event\Subscriber;
use PHPUnit\Event\TestSuite\Started;
use PHPUnit\Event\TestSuite\Finished;

class ExtensionLoader extends Extension implements Subscriber
{
    public function __construct()
    {
        parent::__construct();

        Facade::instance()->registerSubscriber($this);
    }

    public function subscribesTo(): array
    {
        return [
            Started::class => 'onTestSuiteStarted',
            Finished::class => 'onTestSuiteFinished',
        ];
    }

    public function onTestSuiteStarted(Started $event): void
    {
        $this->suiteStarted($event->testSuite()->name());
    }
}
