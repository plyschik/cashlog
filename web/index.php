<?php

use Dotenv\Dotenv;
use CashLog\CashLogApplication;
use CashLog\Controller\SecurityController;
use CashLog\Controller\DashboardController;

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv(__DIR__ . '/..'))->load();

$app = new CashLogApplication([
    'debug' => true
]);

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views',
    'twig.options' => [
        'cache' => __DIR__ . '/../var/cache'
    ]
]);

$app['SecurityController'] = function () use ($app) {
    return new SecurityController($app);
};

$app['DashboardController'] = function () use ($app) {
    return new DashboardController($app);
};

$app->get('/', 'SecurityController:signinAction');
$app->get('/signout', 'SecurityController:signoutAction');
$app->get('/dashboard', 'DashboardController:indexAction');

$app->run();