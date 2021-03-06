<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use CashLog\Utility\Logger;
use CashLog\EventListener\SecurityListener;
use CashLog\EventListener\ApplicationListener;

class CashLogServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $app)
    {
        $app['cashlog.logger'] = function () use ($app) {
            return new Logger($app);
        };
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber(new SecurityListener($app));
        $dispatcher->addSubscriber(new ApplicationListener($app));
    }
}