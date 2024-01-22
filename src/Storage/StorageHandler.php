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

        if (strpos($filename, $workingDirectory) === false) {
            throw new RuntimeException('Test exists in a unexpected directory');
        }

        return substr($filename, strlen($workingDirectory));
    }
}
