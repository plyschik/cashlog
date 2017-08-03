<?php

namespace CashLog\EventListener;

use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SecurityListener implements EventSubscriberInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onAuthenticationFailure()
    {
        $this->app['UserModel']->insertFailedAttempt($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
    }

    public function onAuthenticationSuccess()
    {
        $this->app['UserModel']->truncateFailedAttempts();
    }

    public static function getSubscribedEvents()
    {
        return [
            'security.authentication.failure' => ['onAuthenticationFailure'],
            'security.authentication.success' => ['onAuthenticationSuccess']
        ];
    }
}