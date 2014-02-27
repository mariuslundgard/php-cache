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
        if ($err) {
            return $this->app->call($req);
        }

        $req->cache = new Cache($this->config->get());

        // $res = $this->app->call($req);

        // return $res;

        return $this->app->call($req);
    }
}
