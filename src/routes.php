<?php

use Symfony\Component\HttpFoundation\Request;

$app->before(function () use ($app) {
    if ($app['session']->has('_locale')) {
        $app['translator']->setLocale($app['session']->get('_locale'));
    }
});

$app
    ->get('/locale/{_locale}', function (Request $request) use ($app) {
        $app['session']->set('_locale', $request->getLocale());

        $app['dispatcher']->dispatch('application.localeChange');

        return $app->redirect($app->url('logs.index'));
    })
    ->bind('locale')
;

$app
    ->get('/', 'SecurityController:signinAction')
    ->bind('security.signin')
;

$app
    ->get('/unblock/{key}', 'SecurityController:unblockAction')
    ->bind('security.unblock')
;

$app
    ->match('/profile/changepassword', 'ProfileController:changePasswordAction')
    ->method('GET|POST')
    ->bind('profile.changepassword')
;

$app
    ->match('/logs', 'LogsController:indexAction')
    ->method('GET|POST')
    ->bind('logs.index')
;

$app
    ->match('/logs/edit/{id}', 'LogsController:editAction')
    ->method('GET|POST')
    ->bind('logs.edit')
;

$app
    ->match('/logs/remove', 'LogsController:removeAction')
    ->method('GET|POST')
    ->bind('logs.remove')
;