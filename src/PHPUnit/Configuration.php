<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\PHPUnit;

use Exception;

class Configuration
{
    public static function shouldRunTimeReport(): bool
    {
        global $argv;

        return in_array('--time-report', $argv);
    }

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

        $configKey = array_search('--configuration', $argv, true);
        if ($configKey !== false && isset($argv[(int) $configKey + 1])) {
            $configurationFile = realpath($argv[(int) $configKey + 1]);

            if (!$configurationFile) {
                return null;
            }

            return str_replace('phpunit.xml', '', $configurationFile);
        }

        return null;
    }
}
