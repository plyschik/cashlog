<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use CashLog\Controller\LogsController;
use CashLog\Controller\ProfileController;
use CashLog\Controller\SecurityController;

class ControllersServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['LogsController'] = function () use ($app) {
            return new LogsController($app);
        };

        $app['ProfileController'] = function () use ($app) {
            return new ProfileController($app);
        };

        $app['SecurityController'] = function () use ($app) {
            return new SecurityController($app);
        };
    }
}