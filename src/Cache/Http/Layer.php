<?php

namespace Cache\Http;

use Studio\Http\Layer\AbstractConfigurableLayer; // = Middleware
use Studio\Http\Request;
use Exception;

class Layer implements AbstractConfigurableLayer
{
    public function call(Request $req, Exception $err = null)
    {
        if ($err) {
            return $this->app->call($req);
        }

        return $this->app->call($req);
    }
}
