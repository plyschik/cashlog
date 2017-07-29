<?php

require_once 'prod.providers.php';

$app->register(new \Silex\Provider\VarDumperServiceProvider());

$app->register(new \Silex\Provider\WebProfilerServiceProvider(), [
    'profiler.cache_dir' => __DIR__ . '/../var/cache/profiler',
    'profiler.mount_prefix' => '/_profiler'
]);

$app->register(new \Silex\Provider\HttpFragmentServiceProvider());