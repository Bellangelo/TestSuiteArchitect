<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

if (class_exists('PHPUnit\\Event\\Subscriber')) {
    // PHPUnit 10 or PHPUnit 11
    (new Bellangelo\TestSuiteArchitect\Extensions\ExtensionV10());
} else if(class_exists('PHPUnit\\Framework\\TestListener')) {
    // PHPUnit 9
    (new Bellangelo\TestSuiteArchitect\Extensions\ExtensionV9());
} else {
    throw new RuntimeException('Unsupported PHPUnit version');
}
