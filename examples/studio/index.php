<?php

require __DIR__.'/../../vendor/autoload.php';

function create_app()
{
    /**
     * A Studio app using Cache\Http\Layer (middleware)
     */
    return (new Studio\Http\Application)
    ->get('/*', function ($req, $res) {

        // retrieve cache
        if ($req->cache && $cached = $req->cache->fetch('page')) {
            return $cached;
        }

        // simulate hard work
        usleep(500000); // = 500 ms

        // create view
        $html = '<h1>Hello, world!</h1>';

        // store cache value
        if ($req->cache) {
            // echo '<pre>get from cache</pre>';
            $req->cache->store('page', $html . '<p>Cached: '.gmdate('Y-m-d H:i:s').' GMT</p>');
        }

        // return view
        return $html;

    })
    ->employ('Cache\Http\Layer', [
        // 'type' => 'memcached',
        'type' => 'file',
        'path' => __DIR__.'/cache',
    ]);

}

// putenv('DEBUG=1');
$requests = 0;
$startTime = float_microtime();
$duration = 0;

while ($duration < 1) {

    try {
        $app = create_app();
        $res = $app->call();
        $duration = float_microtime() - $startTime;
        $requests++;
    }

    catch (Exception $err) {
        ob_end_clean();
        echo '<title>'.$err->getMessage().'</title>';
        echo '<h1>'.$err->getMessage().'</h1>';
        exit;
    }
}

echo $res->body;

echo '<pre>' . number_format( $requests / 1 ) . ' req/sec</pre>';
// echo '<pre>Ran ' . $requests . ' times in 1 second</pre>'; 
