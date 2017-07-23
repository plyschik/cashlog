<?php

namespace CashLog\Controller;

use CashLog\CashLogApplication;

abstract class BaseController
{
    protected $app;

    public function __construct(CashLogApplication $app)
    {
        $this->app = $app;
    }
}