<?php

use Dotenv\Dotenv;
use CashLog\CashLogApplication;

require_once __DIR__ . '/../vendor/autoload.php';

(new Dotenv(__DIR__ . '/..'))->load();

$app = new CashLogApplication();

require_once __DIR__ . '/../src/prod.providers.php';

require_once __DIR__ . '/../src/routes.php';

$app->run();