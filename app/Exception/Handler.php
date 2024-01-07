<?php

namespace App\Exception;

use Framework\Exception\ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render(Throwable $throwable)
    {
        return parent::render($throwable);
    }
}