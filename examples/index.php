<?php

require __DIR__.'/../vendor/autoload.php';

use Cache\Cache;

// putenv('DEBUG=1');

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
]);

// var_dump($cache->keys());

//
$cacheVal = isset($_GET['message']) ? urldecode($_GET['message']) : 'Hello, world! #' . uniqid();

?>

<form>
    <input type="text" name="message" value="<?php echo htmlspecialchars($cacheVal); ?>">
    <input type="submit" value="Process data">
</form>

<?php

// prepare cache key
$cacheKey = md5($cacheVal);
$cacheKey = (substr($cacheKey, 0, 2)) . '/' . (substr($cacheKey, 2));

//
if ($cached = $cache->fetch($cacheKey)) {
    die('From cache: ' . $cached);
}

// simulate hard work and network latency . . .
sleep(1);
$cacheVal = '<h1>' . $cacheVal . '</h1>';

// store the cache value
$cache->store($cacheKey, $cacheVal);

// output
echo $cache->fetch($cacheKey);

