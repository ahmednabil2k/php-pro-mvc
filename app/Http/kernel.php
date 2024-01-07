<?php

use Framework\Middleware\CSRFTokenMiddleware;
use Framework\Middleware\PostMaxSizeMiddleware;

return [
    PostMaxSizeMiddleware::class,
    CSRFTokenMiddleware::class,
];