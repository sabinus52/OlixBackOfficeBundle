<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
    ])
    ->withParallel(120, 16, 10)
    ->withPhpLevel(level: 83)
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        deadCode: true,
        naming: false, /* @experimental */
        privatization: false, /* @experimental */
        strictBooleans: true,
        typeDeclarations: true,
        earlyReturn: true,
        instanceOf: true,
        twig: true,
        carbon: false, /* @experimental */
        rectorPreset: true, /* @experimental */
        doctrineCodeQuality: true,
        symfonyCodeQuality: true,
        symfonyConfigs: true,
        phpunitCodeQuality: true,
    )
    ->withSets([
        SetList::GMAGICK_TO_IMAGICK,
        LevelSetList::UP_TO_PHP_83,
        SymfonySetList::SYMFONY_64,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ])
    ->withAttributesSets(
        symfony: true,
        doctrine: true,
        phpunit: true,
        sensiolabs: true,
    )
    ->withSkip([
        DisallowedEmptyRuleFixerRector::class,
        NewlineAfterStatementRector::class,
    ])
;
