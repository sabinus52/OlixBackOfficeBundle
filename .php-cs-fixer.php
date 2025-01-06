<?php

declare(strict_types=1);


$header = file_get_contents(__DIR__.'/.vscode/license.header.txt');

$finder = PhpCsFixer\Finder::create()
    ->ignoreDotFiles(true)
    ->ignoreVCSIgnored(true)
    ->in([
        __DIR__.'/src/',
        __DIR__.'/tests/',
    ])
    ->notPath([
        'Kernel.php',
        'bootstrap.php',
    ])
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@DoctrineAnnotation' => true,
        '@PHP83Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        '@PSR12' => true,
        '@PSR12:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'no_superfluous_phpdoc_tags' => false,
        'native_function_invocation' => false,
        'native_constant_invocation' => false,
        'header_comment' => ['header' => $header, 'comment_type' => 'PHPDoc', 'location' => 'after_declare_strict', 'separate' => 'both'],
        'phpdoc_to_comment' => false,
    ])
    ->setCacheFile(__DIR__.'/var/.php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
