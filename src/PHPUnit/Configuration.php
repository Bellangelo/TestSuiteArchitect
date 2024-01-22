<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\PHPUnit;

class Configuration
{
    public static function getWorkingDirectory(): string
    {
        $configurationDirectory = self::getPHPUnitConfigurationDirectory();

        return $configurationDirectory ?? getcwd();
    }

    public static function getTestsDirectory(): string
    {
        return self::getWorkingDirectory() . '/tests';
    }

    public static function getPHPUnitConfigurationDirectory(): ?string
    {
        global $argv;

        $configKey = array_search('--configuration', $argv);
        if ($configKey !== false && isset($argv[$configKey + 1])) {
            $configurationFile = realpath($argv[$configKey + 1]);

            return str_replace('phpunit.xml', '', $configurationFile);
        }

        return null;
    }
}
