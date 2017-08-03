<?php

namespace CashLog\Utility;

use Silex\Application;

class Logger
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function log($description)
    {
        $this->app['LogModel']->addLog($description);
    }
}