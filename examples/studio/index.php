<?php

require __DIR__.'/../../vendor/autoload.php';

try {

    // putenv('DEBUG=1');

    /**
     * A Studio app using Cache\Http\Layer (middleware)
     */
    (new Studio\Http\Application)
        ->get('/*', function ($req, $res) {

            // retrieve cache
            if ($req->cache && $cached = $req->cache->fetch('page')) {
                return $cached;
            }

            // simulate hard work
            sleep(1);

            // create view
            $html = '<h1>Hello, world!</h1>';

            // store cache value
            $req->cache->store('page', $html . '<p>Cached: '.gmdate('Y-m-d H:i:s').' GMT</p>');

            // return view
            return $html;

        })
        ->employ('Cache\Http\Layer', [
            // 'type' => 'memcached',
            'type' => 'file',
            'path' => __DIR__.'/cache',
        ])
        ->run();
}

catch (Exception $err) {
    ob_end_clean();
    echo '<title>'.$err->getMessage().'</title>';
    echo '<h1>'.$err->getMessage().'</h1>';
}
