# TestSuiteArchitect
Welcome to Test Suite Architect, a powerful extension for PHPUnit designed to optimize your testing process.
This innovative tool offers a smart solution for partitioning and evenly distributing your tests, ensuring
a more efficient and time-effective testing cycle.

## Installation
```
composer require bellangelo/test-suite-architect --dev
```

## Configuration
In your phpunit.xml file, add the following lines:
```xml
<listeners>
    <listener class="Bellangelo\TestSuiteArchitect\Extensions\ExtensionV9" />
</listeners>
```

## Usage
After you have installed and configured the extension,
you can run your tests as usual.\
To generate the time report, you need
to pass the `--time-report` option to the PHPUnit command.
For example:
```
vendor/bin/phpunit --time-report
```

# How to partition your tests
Once you have run your tests, you can partition them by running the following command:
```
vendor/bin/testsuitearchitect --partition {number of partitions}
```
This will create and store x number of test suites in the `.test-suite-architect/test-suites` directory.\
To import the new test suites in your PHPUnit configuration file, you can use the `xi:include` directive.\
Example:
```xml
<phpunit xmlns:xi="http://www.w3.org/2001/XInclude">
    <listeners>
        <listener class="Bellangelo\TestSuiteArchitect\Extensions\ExtensionLoader" />
    </listeners>
    <testsuites>
        <xi:include href=".test-suite-architect/test-suites/test-suite-1.xml" />
        <xi:include href=".test-suite-architect/test-suites/test-suite-2.xml" />
        <xi:include href=".test-suite-architect/test-suites/test-suite-3.xml" />
        <xi:include href=".test-suite-architect/test-suites/test-suite-4.xml" />
    </testsuites>
</phpunit>
```
