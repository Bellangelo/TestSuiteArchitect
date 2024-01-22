<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\PHPUnit;

use Exception;

class Configuration
{
    public static function getWorkingDirectory(): string
    {
        $configurationDirectory = self::getPHPUnitConfigurationDirectory();

        if ($configurationDirectory) {
            return $configurationDirectory;
        }

        if (getcwd()) {
            return getcwd();
        }

        throw new Exception('Could not get working directory');
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

            if (!$configurationFile) {
                return null;
            }

            return str_replace('phpunit.xml', '', $configurationFile);
        }

        return null;
    }
}
