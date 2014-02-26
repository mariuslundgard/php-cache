<?php

namespace Cache;

use Util\Dictionary;
use Exception;

class Cache
{
    protected $config;
    protected $adapter;

    public function __construct(array $config)
    {
        $this->config = $config + [
            'type' => 'dummy',
        ];
    }

    // public function __get($property)
    // {
    //     switch ($property) {

    //         case 'config':
    //             return $this->config;
    //     }
    // }

    public function getAdapter()
    {
        if (null === $this->adapter) {
            switch ($this->config['type']) {

                case 'dummy':
                    $this->adapter = new Adapter\Dummy($this, $this->config);
                    break;

                case 'apc':
                    $this->adapter = new Adapter\Apc($this, $this->config);
                    break;

                case 'file':
                    $this->adapter = new Adapter\File($this, $this->config);
                    break;

                case 'memcached':
                    $this->adapter = new Adapter\Memcached($this, $this->config);
                    break;

                case 'mysql':
                    $this->adapter = new Adapter\MySQL($this, $this->config);
                    break;

                case 'session':
                    $this->adapter = new Adapter\Session($this, $this->config);
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

    public function store($key, $value)
    {
        return $this->getAdapter()->store($key, $value);
    }
}
