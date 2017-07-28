<?php

use Dotenv\Dotenv;
use CashLog\CashLogApplication;

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv(__DIR__ . '/..'))->load();

$app = new CashLogApplication([
    'debug' => true
]);

$app->register(new \Silex\Provider\ServiceControllerServiceProvider());

$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/../views',
    'twig.options' => [
        'cache' => __DIR__ . '/../var/cache',
        'strict_variables' => false
    ]
]);

$app->extend('twig', function ($twig, $app) {
    $twig->addGlobal('SIGNIN_ATTEMPTS', getenv('SIGNIN_ATTEMPTS'));
    $twig->addGlobal('SIGNIN_ATTEMPTS_INTERVAL', getenv('SIGNIN_ATTEMPTS_INTERVAL'));

    return $twig;
});

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
                'default_target_path' => '/logs'
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

$app->register(new \Silex\Provider\SessionServiceProvider(), [
    'session.storage.save_path' => __DIR__ . '/../var/sessions'
]);

$app->register(new \Silex\Provider\FormServiceProvider());

$app->register(new \Silex\Provider\CsrfServiceProvider());

$app->register(new \Silex\Provider\ValidatorServiceProvider(), [
    'validator.validator_service_ids' => [
        'validator.valid_password' => 'validator.valid_password'
    ]
]);

$app->register(new \Silex\Provider\LocaleServiceProvider(), [
    'locale' => 'pl'
]);

$app->register(new Silex\Provider\TranslationServiceProvider(), [
    'locale_fallbacks' => ['pl']
]);

$app->register(new \Silex\Provider\VarDumperServiceProvider());

$app->register(new \CashLog\Provider\ControllersServiceProvider());

$app->register(new \CashLog\Provider\ModelsServiceProvider());

$app->register(new \CashLog\Provider\ConstraintsServiceProvider());

$app->register(new \CashLog\Provider\CashLogServiceProvider());

$app->on('security.authentication.failure', function () use ($app) {
    $app['db']->insert('signin_failed_attempts', [
        'ip_address'    => "INET_ATON('" . $_SERVER['REMOTE_ADDR'] . "')",
        'useragent'     => $_SERVER['HTTP_USER_AGENT']
    ]);
});

$app
    ->get('/', 'SecurityController:signinAction')
    ->bind('security.signin')
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

$app->run();