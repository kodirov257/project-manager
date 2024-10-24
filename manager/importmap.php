<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
    'bootstrap' => [
        'version' => '5.3.3',
    ],
    'bootstrap/dist/css/bootstrap.min.css' => [
        'version' => '5.3.3',
        'type' => 'css',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'jquery' => [
        'version' => '3.7.1',
    ],
    '@coreui/coreui' => [
        'version' => '5.1.2',
    ],
    '@coreui/coreui/dist/css/coreui.min.css' => [
        'version' => '5.1.2',
        'type' => 'css',
    ],
    'perfect-scrollbar' => [
        'version' => '1.5.5',
    ],
    'perfect-scrollbar/css/perfect-scrollbar.min.css' => [
        'version' => '1.5.5',
        'type' => 'css',
    ],
    'simple-line-icons/css/simple-line-icons.min.css' => [
        'version' => '2.5.5',
    ],
    'font-awesome/css/font-awesome.min.css' => [
        'version' => '4.7.0',
        'type' => 'css',
    ],
];
