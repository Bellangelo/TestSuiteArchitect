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

    public static function getPHPUnitConfigurationDirectory(): ?string
    {
        global $argv;

        $configKey = array_search('--configuration', $argv);
        if ($configKey !== false && isset($argv[$configKey + 1])) {
            // Return the path to the configuration file
            return realpath($argv[$configKey + 1]);
        }

        return null;
    }
}
