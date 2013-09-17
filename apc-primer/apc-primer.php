<?php
/**
 * Engine Yard PHP Performance Tools
 *
 * @copyright Copyright 2013 Engine Yard, Inc
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @author Davey Shafik <davey@engineyard.com>
 */

use EngineYard\CallbackFilterIterator;

/**
 * APC Cache Primer
 */

require_once __DIR__ . '/../../../autoload.php';

$config = require 'config.php';

// Authentication
if ($config['auth']['enabled'] || isset($_SERVER['PHP_AUTH_USER']))) {
    if (!isset($_SERVER['PHP_AUTH_USER']) ||
        !isset($_SERVER['PHP_AUTH_PW']) ||
        $_SERVER['PHP_AUTH_USER'] != $config['auth']['username'] ||
        $_SERVER['PHP_AUTH_PW'] != $config['auth']['password']) {
        Header("WWW-Authenticate: Basic realm=\"Login\"");
        Header("HTTP/1.0 401 Unauthorized");

        echo <<<HTML
                <html><body>
                <h1>Rejected!</h1>
                <p>
                    <strong>Wrong Username or Password!</strong>
                </p>
                </body></html>
HTML;
        exit;
    }
}

// Get the start time
$start = microtime(true);

// File counter
$files = 0;

foreach ($config['paths'] as $path) {
    // Get the realpath and check it's valid
    $path = realpath($path);
    if (!$path) {
        continue;
    }

    // Create a directory iterator
    $dir = new RecursiveDirectoryIterator($path);

    // Create a recursive iterator
    $iterator = new RecursiveIteratorIterator($dir);

    $filters = [
        'checkExtension' => function($file) {
            // Check the extension
            $ext = $file->getExtension();
            if (!($ext == 'php' || $ext == 'phtml')) {
                return false;
            }

            return true;
        },
        'ignoreTests' => function($file) {
            // Ensure we're not in a tests directory
            if (stripos($file->getRealPath(), DS.'tests'.DS) !== false) {
                return false;
            }

            return true;
        },
    ];

    // Filter the iterator using our custom filter iterator
    $filter = new CallbackFilterIterator($iterator, $filters);

    // Iterate over the files that match
    foreach ($filter as $file) {
        // Increment our counter
        $files++;

        // Cache the file with APC
        apc_compile_file($file->getRealPath());
    }
}

// Get the end time
$end = microtime(true);

// Remove this file, and the filter from the cache
apc_delete_file([
    __FILE__,
    __DIR__ . '/../src/EngineYard/CallbackFilterIterator.php',
]);

// Display results
echo "Cached $files files in " .($end - $start). " seconds" . PHP_EOL;