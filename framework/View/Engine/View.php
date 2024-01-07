<?php

namespace Framework\View\Engine;

class View
{
    public function __construct(
        protected Engine $engine,
        public string $path,
        public array $data = []
    )
    {}

    public function __toString(): string
    {
        return $this->engine->render($this);
    }
}