#!/usr/bin/env php
<?php

set_time_limit(0);

use Dotenv\Dotenv;
use Silex\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Silex\Provider\DoctrineServiceProvider;
use CashLog\Provider\ModelsServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv(__DIR__ . '/..'))->load();

$app = new Application();

$app->register(new ServiceControllerServiceProvider());

$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver'     => getenv('DATABASE_DRIVER'),
        'host'       => getenv('DATABASE_HOST'),
        'dbname'     => getenv('DATABASE_NAME'),
        'user'       => getenv('DATABASE_USER'),
        'password'   => getenv('DATABASE_PASSWORD'),
        'charset'    => getenv('DATABASE_CHARSET')
    ]
]);

$app->register(new ModelsServiceProvider());

$console = new ConsoleApplication('Cashlog', 'n/a');

$console
    ->register('cashlog:unblock')
    ->setDescription('Signin form unblock.')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $app['UserModel']->truncateFailedAttempts();
        $output->write('Formularz został odblokowany.');
    })
;

$console->run();