<?php

require __DIR__.'/../vendor/autoload.php';

use Cache\Cache;

putenv('DEBUG=1');

$cache = new Cache([
    // 'type' => 'dummy', // default
    'type' => 'file',
    // 'type' => 'session',
    // 'type' => 'memcached',
    // 'type' => 'apc',
    // 'type' => 'mysql',
    // 'pass' => 'Does-the-moon-think-why?',
    // 'name' => 'dev_abstraction',
    // 'collection' => 'cache',
    // 'path' => __DIR__.'/cache',
]);

foreach ($cache->keys() as $key) {

    // echo $key;
    // echo '<br />';
    echo $cache->delete($key);
    // d($cache->config['path'] . '/' . $key);
    // exit;
}
