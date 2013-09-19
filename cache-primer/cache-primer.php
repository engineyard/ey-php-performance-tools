<?php
namespace EngineYard;

if (file_exists('../vendor')) {
    // Probably a git checkout
    require '../vendor/autoload.php';
} elseif (file_exists('../autoload.php')) {
    // Running the composer-installed executable
    require '../require.php';
} elseif (file_exists('../../autoload.php')) {
    // Running the composer-installed script directly
    require '../../autoload.php';
}

echo "=== Cache Primer ===" . PHP_EOL;

$config = require 'config.php';

// Get the start time
$start = microtime(true);

// Get the number of expected requests
$expectedUrls = sizeof($config['urls']);

echo "Attempting to cache $expectedUrls URLs:" . PHP_EOL;

if (class_exists('HttpRequestPool') && $config['threads'] > 0) {

    echo "Running in parallel with " .$config['threads']. " concurrent requests" . PHP_EOL;

    // Run in parallel
    $requests = [];

    // Create HttpRequest objects for each URL
    foreach ($config['urls'] as $key => $url) {
        try {
            $request = new \HttpRequest($url, \HttpRequest::METH_GET);
            $request->key = $key;
            $request->setOptions(['redirect' => 3]);
            $requests[] = $request;
        } catch (\HttpException $e) {
            unset($config['urls'][$key]);
        }
    }

    if (sizeof($requests) == 0) {
        echo "Unable to create HTTP client" . PHP_EOL;
        exit;
    }

    // We know we have to iterate at least once, so use do... while
    do {
        // Get the current batch of requests
        $threads = array_splice($requests, 0, $config['threads']);

        // Create a new HttpRequestPool and attach the requests
        $pool = new \HttpRequestPool();
        foreach ($threads as $request) {
            $pool->attach($request);
        }

        // Send the requests and wait till they are all complete
        try {
            $pool->send();

            while (sizeof($pool->getFinishedRequests()) < sizeof($threads)) ;

            foreach ($pool as $request) {
                // Display progress
                if ($request->getResponseCode() == 200) {
                    echo '.';
                } else {
                    unset($config['urls'][$request->key]);
                    echo '!';
                }
            }
        } catch (\HttpRequestPoolException $e) { }
    } while (sizeof($requests) > 0);
} else {
    foreach ($config['urls'] as $key => $url) {
        if (!@file_get_contents($url)) {
            echo "!";
            unset($config['urls'][$key]);
        } else {
            echo ".";
        }
    }
}

// Get the end time
$end = microtime(true);

// Display results
$urls = sizeof($config['urls']);
echo PHP_EOL;
echo "Cached $urls URLs in " .($end - $start). " seconds" . PHP_EOL;
echo "Encountered " .($expectedUrls - $urls). " errors" . PHP_EOL;