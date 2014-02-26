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
        $iter = new APCIterator($this->config['type']);
        $ret = [];

        foreach ($iter as $item) {
            $ret[] = $item['key'];
        }

        return $ret;
    }

    public function fetch($key)
    {
        return apc_fetch($key);
    }

    public function store($key, $value, $expiration = null)
    {
        apc_store($key, $value);
    }
}
