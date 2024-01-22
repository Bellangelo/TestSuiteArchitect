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

    public static function getAbsoluteFolder(string $folderName): string
    {
        return __DIR__ . '/../../../../../' . $folderName;
    }

    public static function convertToRelativePath(string $filename): string
    {
        $workingDirectory = Configuration::getWorkingDirectory();

        return self::getRelativePath($filename, $workingDirectory);
    }

    private static function getRelativePath(string $from, string $to): string
    {
        // Normalize directory separators
        $from = str_replace('\\', '/', realpath($from));
        $to = str_replace('\\', '/', realpath($to));

        // Create arrays from paths and filter out empty values
        $fromParts = array_filter(explode('/', $from), 'strlen');
        $toParts = array_filter(explode('/', $to), 'strlen');

        // Count of same path parts
        $samePartsCount = 0;
        foreach ($fromParts as $i => $part) {
            if (isset($toParts[$i]) && $toParts[$i] == $part) {
                $samePartsCount++;
            } else {
                break;
            }
        }

        // Calculate '..' parts to prepend
        $parentDirs = array_fill(0, count($fromParts) - $samePartsCount, '..');

        // Build relative path
        $relativePathParts = array_merge($parentDirs, array_slice($toParts, $samePartsCount));
        $relativePath = implode('/', $relativePathParts);

        return $relativePath;
    }
}
