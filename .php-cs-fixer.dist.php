<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__.'/src', 
        __DIR__.'/tests',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        '@Symfony' => true,
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        '@PHP83Migration' => true,
    ])
    ->setFinder($finder)
;