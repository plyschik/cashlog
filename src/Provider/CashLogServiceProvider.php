<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CashLogServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['availableSigninAttempts'] = function () use ($app) {
            return $app['db']->fetchColumn("SELECT " . getenv('SIGNIN_ATTEMPTS') . " - (SELECT COUNT(*) FROM signin_failed_attempts)");
        };

        $app->on('security.authentication.failure', function () use ($app) {
            $app['UserModel']->insertFailedAttempt($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
        });

        $app->on('security.authentication.success', function () use ($app) {
            $app['UserModel']->truncateFailedAttempts();
        });
    }
}