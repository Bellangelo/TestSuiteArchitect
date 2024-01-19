<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Storage;

abstract class StorageHandler
{
    protected const CONFIGURATION_FOLDER_NAME = '.test-suite-architect';

    public function __construct()
    {
        $this->initializeFolder();
    }

    protected function initializeFolder(): void
    {
        $absolutePath = $this->getAbsoluteFolder(self::CONFIGURATION_FOLDER_NAME);

        if (!file_exists($absolutePath)) {
            mkdir($absolutePath);
        }
    }

    protected function getAbsolutePath(string $fileName): string
    {
        return $this->getAbsoluteFolder(self::CONFIGURATION_FOLDER_NAME) . '/' . $fileName;
    }

    private function getAbsoluteFolder(string $folderName): string
    {
        return __DIR__ . './../../../../' . $folderName;
    }
}