<?php


$app = \Framework\Contracts\Http\App::getInstance();

$app->bind('paths.base', fn() => realpath(__DIR__ . '/../'));

$app->bind(\Framework\Exception\ExceptionHandler::class, \App\Exception\Handler::class);

return $app;