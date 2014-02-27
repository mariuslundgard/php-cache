<?php

namespace Cache\Http;

use Studio\Http\Layer\AbstractConfigurableLayer; // = Middleware
use Studio\Http\Request;
use Cache\Cache;
use Exception;

class Layer extends AbstractConfigurableLayer
{
    public function call(Request $req, Exception $err = null)
    {
        if ($err || ! $this->config->get('use', true)) {
            return $this->app->call($req);
        }

        $req->cache = new Cache($this->config->get());

        return $this->app->call($req);
    }
}
