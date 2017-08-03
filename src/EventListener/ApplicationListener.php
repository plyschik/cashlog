<?php

namespace CashLog\EventListener;

use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplicationListener implements EventSubscriberInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public static function getSubscribedEvents()
    {
        return [];
    }
}