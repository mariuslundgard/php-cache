<?php

namespace Cache\Adapter;

use Cache\Cache;

class Session implements AdapterInterface
{
    public function __construct(Cache $cache, array $config = [])
    {
        $this->cache = $cache;
        $this->config = $config + [
            'id' => '__CACHE__',
        ];
    }

    public function keys()
    {
        $this->_start();
        return array_keys($_SESSION[$this->config['id']]);
    }

    public function delete($key)
    {
        foreach($this->keys() as $k) {
            if (0 === strpos($k, $key) && '/' === substr($k, strlen($key), 1)) {
                unset($_SESSION[$this->config['id']][$k]);
            }
        }

        unset($_SESSION[$this->config['id']][$key]);
    }

    public function fetch($key)
    {
        $this->_start();
        return isset($_SESSION[$this->config['id']][$key]) ? $_SESSION[$this->config['id']][$key] : null;
    }

    public function store($key, $value, $expiration = null)
    {
        $this->_start();
        $_SESSION[$this->config['id']][$key] = $value;
    }

    protected function _start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
