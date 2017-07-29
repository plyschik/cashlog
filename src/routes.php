<?php

$app
    ->get('/', 'SecurityController:signinAction')
    ->bind('security.signin')
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