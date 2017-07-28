<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CashLogServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['availableSigninAttempts'] = function () use ($app) {
            return $app['db']->fetchColumn("SELECT " . getenv('SIGNIN_ATTEMPTS') . " - (SELECT COUNT(*) FROM signin_failed_attempts WHERE datetime >= DATE_SUB(NOW(), INTERVAL " . getenv('SIGNIN_ATTEMPTS_INTERVAL') . " MINUTE))");
        };
    }
}