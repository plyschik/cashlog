<?php

use Dotenv\Dotenv;
use CashLog\Model\User;
use CashLog\Model\Operation;
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
            'users' => function () use ($app) {
                return new \CashLog\Security\UserProvider($app['db']);
            }
        ]
    ],
    'security.access_rules' => [
        ['^/signin$', 'IS_AUTHENTICATED_ANONYMOUSLY'],
        ['^/.+$', 'ROLE_USER']
    ],
    'security.default_encoder' => function () use ($app) {
        return $app['security.encoder.bcrypt'];
    }
]);

$app->register(new \Silex\Provider\CsrfServiceProvider());

$app->register(new \Silex\Provider\LocaleServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));

$app->register(new \Silex\Provider\ValidatorServiceProvider());

$app->register(new \Silex\Provider\FormServiceProvider());

$app['SecurityController'] = function () use ($app) {
    return new SecurityController($app);
};

$app['DashboardController'] = function () use ($app) {
    return new DashboardController($app);
};

$app['OperationModel'] = function () use ($app) {
    return new Operation($app['db']);
};

$app['UserModel'] = function () use ($app) {
    return new User($app['db']);
};

$app
    ->get('/', 'SecurityController:signinAction')
    ->bind('homepage')
;

$app
    ->match('/dashboard', 'DashboardController:indexAction')
    ->method('GET|POST')
    ->bind('dashboard')
;

$app
    ->match('/dashboard/edit/{id}', 'DashboardController:editAction')
    ->method('GET|POST')
    ->bind('edit')
;

$app
    ->match('/dashboard/remove/{id}', 'DashboardController:removeAction')
    ->method('GET|POST')
    ->bind('remove')
;

$app->run();