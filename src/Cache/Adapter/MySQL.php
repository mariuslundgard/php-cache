<?php

namespace Cache\Adapter;

use Cache\Cache;
use Abstraction\MySQL\Client;
use Abstraction\MySQL\Db;
use Abstraction\Collection;

class MySQL implements AdapterInterface
{
    protected $cache;
    protected $client;
    protected $db;
    protected $collection;

    public function __construct(Cache $cache, array $config)
    {
        $this->cache = $cache;

        $this->config = $config + [
            'user' => 'root',
            'pass' => null,
            'name' => 'cache',
            'collection' => 'data',
        ];
    }

    public function keys()
    {
        $ret = [];
        foreach ($this->getCollection()->find([], ['key']) as $row) {
            $ret[] = $row['key'];
        }
        return $ret;
    }

    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client($this->config);
        }

        return $this->client;
    }

    public function getDb()
    {
        if (null === $this->db) {
            $this->db = new Db($this->getClient(), $this->config['name']);
        }

        return $this->db;
    }

    public function getCollection()
    {
        if (null === $this->collection) {
            $this->collection = new Collection($this->getDb(), $this->config['collection']);
        }

        return $this->collection;
    }

    public function fetch($key)
    {
        if ($result = $this->getCollection()->findOne(compact('key'))) {
            return unserialize($result['value']);
        }

        return null;
    }

    public function store($key, $value, $expiration = null)
    {
        $value = serialize($value);

        if ($result = $this->getCollection()->findOne(compact('key', 'expiration'))) {
            if ($value !== $result['value'] || $expiration !== $result['expiration']) {
                return $this->getCollection()->update(compact('key'), compact('value', 'expiration'));
            } else {
                return true;
            }
        } else {
            return $this->getCollection()->insert(compact('key', 'value', 'expiration'));
        }

        return false;
    }

    public function delete($key)
    {
        // $value = serialize($value);

        if ($result = $this->getCollection()->findOne(compact('key'))) {
            return $this->getCollection()->remove(compact('key'));
        } else {
            return true;
            // return $this->getCollection()->insert(compact('key', 'value', 'expiration'));
        }

        return false;
    }
}
