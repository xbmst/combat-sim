<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->notPath([
        'config/bundles.php',
        'config/reference.php',
        'config/preload.php',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        '@Symfony' => true,
        '@PHP84Migration' => true,
        '@PHP80Migration:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const']
        ],
        'no_unused_imports' => true,
        'declare_strict_types' => true,
        'strict_param' => true,
        'no_leading_import_slash' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'php_unit_method_casing' => [
            'case' => 'snake_case',
        ],
        'visibility_required' => [
            'elements' => ['property', 'method', 'const']
        ],
        'nullable_type_declaration_for_default_null_value' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arguments', 'parameters'],
        ],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => false,
        ],
        'ordered_types' => ['sort_algorithm' => 'alpha'],
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'one',
                'method' => 'one',
                'property' => 'one',
                'trait_import' => 'none',
            ],
        ],
        'protected_to_private' => true,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => true,
            'remove_inheritdoc' => false,
        ],
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
            'less_and_greater' => false,
        ],
        'phpdoc_align' => [
            'align' => 'left',
        ],
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;
