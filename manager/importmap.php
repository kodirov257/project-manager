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
        'version' => '5.1.0',
    ],
    '@coreui/coreui/dist/css/coreui.min.css' => [
        'version' => '5.1.0',
        'type' => 'css',
    ],
    '@coreui/icons' => [
        'version' => '3.0.1',
    ],
    '@coreui/icons/css/all.min.css' => [
        'version' => '3.0.1',
        'type' => 'css',
    ],
    'simplebar' => [
        'version' => '6.2.7',
    ],
    'simplebar-core' => [
        'version' => '1.2.6',
    ],
    'simplebar/dist/simplebar.min.css' => [
        'version' => '6.2.7',
        'type' => 'css',
    ],
    'lodash-es' => [
        'version' => '4.17.21',
    ],
    'simplebar-core/dist/simplebar.min.css' => [
        'version' => '1.2.6',
        'type' => 'css',
    ],
];
