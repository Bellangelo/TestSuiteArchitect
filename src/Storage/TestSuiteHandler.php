<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Storage;

class TestSuiteHandler extends StorageHandler
{
    private const PARTITIONS_FOLDER = 'partitions';

    public function __construct()
    {
        parent::__construct();

        $this->initializeFolder(
            $this->getAbsolutePath(self::PARTITIONS_FOLDER)
        );
    }

    public function writePartitions(array $partitions): void
    {
        $this->deleteOldPartitions();

        foreach ($partitions as $index => $partition) {
            $this->writePartition($index+1, $partition);
        }
    }

    private function writePartition(int $index, array $partition): void
    {
        $file = fopen($this->getAbsolutePath(
            self::PARTITIONS_FOLDER . '/partition-' . $index . '.csv'),
                      'w'
        );

        foreach ($partition['tests'] as $test) {
            fputcsv($file, [$test]);
        }

        fclose($file);
    }

    private function deleteOldPartitions(): void
    {
        $files = glob($this->getAbsolutePath(self::PARTITIONS_FOLDER) . '/partition-*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
