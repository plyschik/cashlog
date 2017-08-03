<?php

namespace CashLog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use CashLog\EventListener\CashLogListener;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CashLogServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $app)
    {
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber(new CashLogListener($app));
    }
}