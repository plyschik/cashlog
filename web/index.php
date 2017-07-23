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

$app->register(new \Silex\Provider\DoctrineServiceProvider(), [
   'db.options' => [
       'driver'     => getenv('DATABASE_DRIVER'),
       'host'       => getenv('DATABASE_HOST'),
       'dbname'     => getenv('DATABASE_NAME'),
       'user'       => getenv('DATABASE_USER'),
       'password'   => getenv('DATABASE_PASSWORD'),
       'charset'    => getenv('DATABASE_CHARSET')
   ] 
]);

$app->register(new \Silex\Provider\SessionServiceProvider(), [
    'session.storage.save_path' => __DIR__ . '/../var/sessions'
]);

$app->register(new \Silex\Provider\SecurityServiceProvider(), [
    'security.firewalls' => [
        'signin' => [
            'pattern' => '^/$',
            'anonymous' => true
        ],
        'secured' => [
            'pattern' => '^/.*$',
            'anonymous' => true,
            'form' => [
                'login_path' => '/',
                'check_path' => '/signin',
                'default_target_path' => '/dashboard'
            ],
            'logout' => [
                'logout_path' => '/signout',
                'invalidate_session' => true
            ],
            'users' => [
                'cashlog' => ['ROLE_SUPERUSER', 'password']
            ]
        ]
    ],
    'security.access_rules' => [
        ['^/signin$', 'IS_AUTHENTICATED_ANONYMOUSLY'],
        ['^/.+$', 'ROLE_SUPERUSER']
    ],
    'security.default_encoder' => function () use ($app) {
        return new \Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder();
    }
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