<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/bin', __DIR__ . '/src'])
    ->withPreparedSets(
        true,
        true,
        true,
        true,
        true,
        true,
        true
    )
    ->withPhpSets()
    ->withRootFiles()
    ->withImportNames(true);
