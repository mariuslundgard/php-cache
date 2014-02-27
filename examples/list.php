<?php

require __DIR__.'/../vendor/autoload.php';

use Cache\Cache;

putenv('DEBUG=1');

$cache = new Cache([
    // 'type' => 'dummy', // default
    // 'type' => 'file',
    // 'type' => 'session',
    // 'type' => 'memcached',
    // 'type' => 'apc',
    // 'type' => 'mysql',
    // 'pass' => 'Does-the-moon-think-why?',
    // 'name' => 'dev_abstraction',
    // 'collection' => 'cache',
    // 'path' => __DIR__.'/cache',
]);

if (isset($_GET['delete'])) {
    $delKey = $_GET['delete'];
    $cache->delete($delKey);
    header('Location: ' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

$keys = $cache->keys();

foreach ($keys as $key) {
    echo '<p>'.$key.' <a href="?delete=' . $key . '">delete</a></p>';
}

if ( ! count($keys)) {
    echo '<p>No cache keys</p>';
}
