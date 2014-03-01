<?php

namespace Cache\Adapter;

use Cache\Cache;
use APCIterator;

class Apc implements AdapterInterface
{
    protected $cache;

    public function __construct(Cache $cache, array $config = [])
    {
        $this->cache = $cache;
        $this->config = $config + [
            'type' => 'user',
        ];
    }

    public function keys()
    {
        $ret = [];

        $iterator = new APCIterator($this->config['type'], null, APC_ITER_KEY);

        foreach($iterator as $entry_name) {
            $ret[] = $entry_name['key'];
        }

        return $ret;
    }

    public function delete($key)
    {
        $iterator = new APCIterator($this->config['type'], '#^'.$key.'/#', APC_ITER_KEY);

        foreach($iterator as $entryKey) {
            // if (0 === strpos($k, $key) && '/' === substr($k, strlen($key), 1)) {
            //     $this->getClient()->delete($k);
            // }
            // echo $entryKey;
            // $ret[] = $entryKey['key'];
            // apc_delete($entryKey);

            d('delete apc entry key? ' . $entryKey);
        }
        // exit;

        return apc_delete($key);
    }

    public function fetch($key)
    {
        return apc_fetch($key);
    }

    public function store($key, $value, $expiration = null)
    {
        // expiration = time to live
        apc_store($key, $value, $expiration);
    }
}
