<?php

namespace Cache\Adapter;

use Cache\Cache;
use Memcached as Client;

class Memcached implements AdapterInterface
{
    protected $cache;
    protected $client;

    public function __construct(Cache $cache, array $config)
    {
        $this->cache = $cache;

        $this->config = $config + [
            'id'   => null,
            'host' => 'localhost',
            'port' => 11211,
        ];
    }

    public function keys()
    {
        return $this->getClient()->getAllKeys();
    }

    public function delete($key)
    {
        // delete child keys
        foreach($this->keys() as $k) {
            if (0 === strpos($k, $key) && '/' === substr($k, strlen($key), 1)) {
                $this->getClient()->delete($k);
            }
        }

        return $this->getClient()->delete($key);
    }

    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client($this->config['id']);
            $this->client->addServer($this->config['host'], $this->config['port']);
        }

        return $this->client;
    }

    public function fetch($key)
    {
        // TODO: remove all matching paths
        // $key/*
        return $this->getClient()->get($key);
    }

    public function store($key, $value, $expiration = null)
    {
        return $this->getClient()->set($key, $value, $expiration = null);
    }
}
