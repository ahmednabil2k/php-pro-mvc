<?php

namespace Framework\Contracts\Kernel;
use Framework\Contracts\Http\Request;

interface HttpKernel
{
    public function bootstrap();

    public function handle(Request $request);

    public function getApplication();
}