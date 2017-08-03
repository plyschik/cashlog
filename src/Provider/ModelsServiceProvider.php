<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use CashLog\Model\Log;
use CashLog\Model\User;
use CashLog\Model\Operation;

class ModelsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['UserModel'] = function () use ($app) {
            return new User($app['db']);
        };

        $app['OperationModel'] = function () use ($app) {
            return new Operation($app['db']);
        };

        $app['LogModel'] = function () use ($app) {
            return new Log($app['db']);
        };
    }
}