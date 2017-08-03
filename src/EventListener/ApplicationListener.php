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

    public function onNewOperation()
    {
        $this->app['LogModel']->addLog('Dodano nowy zapis operacji.');
    }

    public function onEditedOperation()
    {
        $this->app['LogModel']->addLog('Dokonano edycji zapisu operacji.');
    }

    public function onRemovedOperation()
    {
        $this->app['LogModel']->addLog('Dokonano usunięcia zapisu operacji.');
    }

    public function onLocaleChange()
    {
        $this->app['LogModel']->addLog('Dokonano zmiany języka interfejsu.');
    }

    public function onPasswordChange()
    {
        $this->app['LogModel']->addLog('Dokonano zmiany hasła.');
    }

    public static function getSubscribedEvents()
    {
        return [
            'application.newOperation'      => 'onNewOperation',
            'application.editedOperation'   => 'onEditedOperation',
            'application.removedOperation'  => 'onRemovedOperation',
            'application.passwordChange'    => 'onPasswordChange',
            'application.localeChange'      => 'onLocaleChange'
        ];
    }
}