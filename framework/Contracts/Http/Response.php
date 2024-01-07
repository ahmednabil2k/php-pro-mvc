<?php

namespace Framework\Contracts\Http;

class Response
{
    protected mixed $response;
    public function __construct(mixed $response)
    {
        $this->response = $response;
    }

    public function send()
    {
        print $this->response;
    }
}