<?php

namespace Cache\Adapter;

interface AdapterInterface
{
    public function keys();
    public function delete($key);
    public function fetch($key);
    public function store($key, $value, $expiration);    
}
