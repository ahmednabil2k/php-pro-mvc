<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = new \Framework\Contracts\Kernel\Console($app);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);
