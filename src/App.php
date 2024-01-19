<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect;

use Minicli\App as MinicliApp;
use Minicli\Command\CommandCall;

class App
{
    private MinicliApp $app;

    public function __construct()
    {
        $this->initApp();
    }

    public function runCommand(CommandCall $input): void
    {
        $this->getApp()->runCommand($input->getRawArgs());
    }

    private function setApp(MinicliApp $app): void
    {
        $this->app = $app;
    }

    private function getApp(): MinicliApp
    {
        return $this->app;
    }

    private function initApp(): void
    {
        $app = new MinicliApp(
            [
                'app_path' => __DIR__ . '/Commands/',
            ],
            './testsuitearchitect'
        );

        $this->setApp($app);
    }
}
