<?php

namespace Cache;

use Util\Dictionary;
use Exception;

class Cache implements Adapter\AdapterInterface
{
    protected $config;
    protected $adapter;

    public function __construct(array $config)
    {
        $this->config = new Dictionary([
            'type' => 'dummy',
        ]);

        $this->configure($config);
    }

    public function configure(array $config)
    {
        $this->config->merge($config);
    }

    public function __get($property)
    {
        switch ($property) {

            case 'config':
                return $this->adapter->config + $this->config->get();
        }
    }

    public function getAdapter()
    {
        if (null === $this->adapter) {
            switch ($this->config['type']) {

                case 'dummy':
                    $this->adapter = new Adapter\Dummy($this, $this->config->get());
                    break;

                case 'apc':
                    $this->adapter = new Adapter\Apc($this, $this->config->get());
                    break;

                case 'file':
                    $this->adapter = new Adapter\File($this, $this->config->get());
                    break;

                case 'memcached':
                    $this->adapter = new Adapter\Memcached($this, $this->config->get());
                    break;

                case 'mysql':
                    $this->adapter = new Adapter\MySQL($this, $this->config->get());
                    break;

                case 'session':
                    $this->adapter = new Adapter\Session($this, $this->config->get());
                    break;

                default:
                    throw new Exception('Unknown cache type: ' . $this->config['type']);
            }
        }

        return $this->adapter;
    }

    public function keys()
    {
        return $this->getAdapter()->keys();
    }

    public function fetch($key)
    {
        return $this->getAdapter()->fetch($key);
    }

    public function store($key, $value, $expiration = null)
    {
        return $this->getAdapter()->store($key, $value, $expiration);
    }

    public function delete($key)
    {
        return $this->getAdapter()->delete($key);
    }
}
