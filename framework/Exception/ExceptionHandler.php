<?php

namespace Framework\Exception;

use Framework\Validation\ValidationException;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ExceptionHandler
{
    /**
     * @throws Throwable
     */
    public function render(Throwable $throwable)
    {
        if ($throwable instanceof ValidationException) {
            $this->showValidationException($throwable);
        }

        if (config('app.app_debug')) {
            $this->showFriendlyThrowable($throwable);
        }
    }

    public function showValidationException(ValidationException $exception)
    {
        if (app('session')) {
            app('session')->put('errors', $exception->getErrors());
        }
        redirect(app('request')->server('HTTP_REFERER'));
    }

    /**
     * @throws Throwable
     */
    public function showFriendlyThrowable(Throwable $throwable)
    {
        $whoops = new Run();
        $whoops->pushHandler(new PrettyPageHandler());
        $whoops->register();
        throw $throwable;
    }

}