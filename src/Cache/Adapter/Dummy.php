<?php

namespace Cache\Adapter;

class Dummy implements AdapterInterface
{
    protected $data = [];

    public function keys()
    {
        return array_keys($this->data);
    }

    public function delete($key)
    {
        unset($this->data[$key]);
    }

    public function fetch($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function store($key, $value, $expiration = null)
    {
        $this->data[$key] = $value;
    }
}
