<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Storage;

use Bellangelo\TestSuiteArchitect\PHPUnit\Configuration;
use RuntimeException;

abstract class StorageHandler
{
    protected const CONFIGURATION_FOLDER_NAME = '.test-suite-architect';

    public function __construct()
    {
        $configurationFolder = static::getAbsoluteFolder(self::CONFIGURATION_FOLDER_NAME);

        if (!$configurationFolder) {
            throw new RuntimeException('Unable to find configuration folder');
        }

        $this->initializeFolder($configurationFolder);
    }

    protected function initializeFolder(string $folder): void
    {
        if (!file_exists($folder) && !mkdir($folder)) {
            throw new RuntimeException('Unable to create configuration folder');
        }
    }

    protected function getAbsolutePath(string $fileName): string
    {
        return static::getAbsoluteFolder(self::CONFIGURATION_FOLDER_NAME) . '/' . $fileName;
    }

    public static function getAbsoluteFolder(string $folderName): ?string
    {
        $path = realpath(__DIR__ . '/../../../../../' . $folderName);

        return $path ?: null;
    }

    public static function getRelativePathBasedOnTests(string $filename): string
    {
        $workingDirectory = Configuration::getWorkingDirectory();

        return self::getRelativePath($filename, $workingDirectory);
    }

    private static function getRelativePath(string $from, string $to): string
    {
        $from = realpath($from);
        $to = realpath($to);

        if (!$from || !$to) {
            throw new RuntimeException('Unable to get relative path');
        }

        // Normalize directory separators and remove trailing slashes
        $from = rtrim(str_replace('\\', '/', $from), '/');
        $to = rtrim(str_replace('\\', '/', $to), '/');

        // Create arrays from paths and filter out empty values
        $fromParts = array_filter(explode('/', $from), 'strlen');
        $toParts = array_filter(explode('/', $to), 'strlen');

        // Count of same path parts
        $samePartsCount = 0;
        foreach ($toParts as $i => $part) {
            if (isset($fromParts[$i]) && $fromParts[$i] == $part) {
                ++$samePartsCount;
            } else {
                break;
            }
        }

        // Calculate '..' parts to prepend
        $parentDirs = array_fill(0, count($toParts) - $samePartsCount, '..');

        // Remove the 'from' directory parts that are the same
        $fromParts = array_slice($fromParts, $samePartsCount);

        // Build relative path
        /** @phpstan-ignore-next-line */
        $relativePath = implode('/', array_merge($parentDirs, $fromParts));

        return $relativePath;
    }
}
