<?php

use Framework\Routing\Router;

return function (Router $router) {

    $router->get(
        '/hello/me',
        function () use ($router){
            echo "Hello world";
        }
    );

};
