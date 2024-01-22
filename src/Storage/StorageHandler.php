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
        $this->initializeFolder(
            $this->getAbsoluteFolder(self::CONFIGURATION_FOLDER_NAME)
        );
    }

    protected function initializeFolder(string $folder): void
    {
        if (!file_exists($folder)) {
            if (!mkdir($folder)) {
                throw new RuntimeException('Unable to create configuration folder');
            }
        }
    }

    protected function getAbsolutePath(string $fileName): string
    {
        return $this->getAbsoluteFolder(self::CONFIGURATION_FOLDER_NAME) . '/' . $fileName;
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
        // Normalize directory separators and remove trailing slashes
        $from = rtrim(str_replace('\\', '/', realpath($from)), '/');
        $to = rtrim(str_replace('\\', '/', realpath($to)), '/');

        // Create arrays from paths and filter out empty values
        $fromParts = array_filter(explode('/', $from), 'strlen');
        $toParts = array_filter(explode('/', $to), 'strlen');

        // Count of same path parts
        $samePartsCount = 0;
        foreach ($toParts as $i => $part) {
            if (isset($fromParts[$i]) && $fromParts[$i] == $part) {
                $samePartsCount++;
            } else {
                break;
            }
        }

        // Calculate '..' parts to prepend
        $parentDirs = array_fill(0, count($toParts) - $samePartsCount, '..');

        // Remove the 'from' directory parts that are the same
        $fromParts = array_slice($fromParts, $samePartsCount);

        // Build relative path
        $relativePath = implode('/', array_merge($parentDirs, $fromParts));

        return $relativePath;
    }
}
