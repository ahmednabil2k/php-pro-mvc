<?php

use Framework\Contracts\Http\Request;

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = new \Framework\Contracts\Kernel\Http($app);

$response = $kernel->handle(Request::capture());

$response->send();



