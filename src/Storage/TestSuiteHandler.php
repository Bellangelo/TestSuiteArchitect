<?php

declare(strict_types=1);

namespace Bellangelo\TestSuiteArchitect\Storage;

use Bellangelo\TestSuiteArchitect\ValueObjects\TestTimer;
use DOMDocument;

class TestSuiteHandler extends StorageHandler
{
    private const TEST_SUITES_FOLDER = 'test-suites';

    public function __construct()
    {
        parent::__construct();

        $this->initializeFolder(
            $this->getAbsolutePath(self::TEST_SUITES_FOLDER)
        );
    }

    public function writeTestSuites(array $partitions): void
    {
        $this->deleteOldTestSuites();

        foreach ($partitions as $index => $partition) {
            $this->writeTestSuite($index+1, $partition);
        }
    }

    private function writeTestSuite(int $index, array $partition): void
    {
        $domtree = new DOMDocument('1.0', 'UTF-8');

        $testSuite = $domtree->createElement('testsuite');
        $testSuite->setAttribute('name', 'test-suite-' . $index);
        $testSuite = $domtree->appendChild($testSuite);

        /** @var TestTimer $test */
        foreach ($partition as $test) {
            $file = $domtree->createElement('file');
            $file->nodeValue = $test->getName();
            $testSuite->appendChild($file);
        }

        $domtree->save(
            $this->getAbsolutePath(
                self::TEST_SUITES_FOLDER . '/test-suite-' . $index . '.xml'
            )
        );
    }

    private function deleteOldTestSuites(): void
    {
        $files = glob($this->getAbsolutePath(self::TEST_SUITES_FOLDER) . '/test-suite-*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}
