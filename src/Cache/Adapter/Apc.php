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
            'apcType' => 'user',
        ];
    }

    public function keys()
    {
        $ret = [];

        $iterator = new APCIterator($this->config['apcType'], null, APC_ITER_KEY);

        foreach($iterator as $entry_name) {
            $ret[] = $entry_name['key'];
        }

        return $ret;
    }

    public function delete($key)
    {
        $iterator = new APCIterator($this->config['apcType'], '#^'.$key.'/#', APC_ITER_KEY);

        foreach($iterator as $entry_name) {
            echo $entry_name;
            // $ret[] = $entry_name['key'];
        }
        exit;

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
