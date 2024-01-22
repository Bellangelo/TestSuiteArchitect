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

        $numberOfPartitions = count($partitions);

        foreach ($partitions as $index => $partition) {
            $normalisedIndex = $index + 1;
            $this->writeTestSuiteForNewFiles($numberOfPartitions, $normalisedIndex);
            $this->writeTestSuite($normalisedIndex, $partition);
        }
    }

    private function writeTestSuiteForNewFiles(int $numberOfPartitions, $index): void
    {
        $template = file_get_contents(__DIR__ . '/../PHPUnit/templates/NewFilesTestSuiteTemplate.php');
        $template = str_replace(
            [
                '{{numberOfPartitions}}',
                '{{partition}}'
            ],
            [
                $numberOfPartitions,
                $index
            ],
            $template
        );

        file_put_contents(
            $this->getAbsolutePath(self::TEST_SUITES_FOLDER . '/' . $this->getFilenameForNewFilesSuite($index)),
            $template
        );
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

        // Add custom test suite for new files.
        $file = $domtree->createElement('file');
        $file->nodeValue = $this->getFilenameForNewFilesSuite($index);
        $testSuite->appendChild($file);

        $domtree->save(
            $this->getAbsolutePath(
                self::TEST_SUITES_FOLDER . '/test-suite-' . $index . '.xml'
            )
        );
    }

    private function deleteOldTestSuites(): void
    {
        $files = glob($this->getAbsolutePath(self::TEST_SUITES_FOLDER) . '/*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    private function getFilenameForNewFilesSuite(int $index): string
    {
        return 'NewFilesTestSuite' . $index . '.php';
    }
}
