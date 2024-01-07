<?php

namespace Framework\Queue;

use Closure;

interface QueueDriver
{
    public function push(Job|Closure $job): int;
    public function pull();

    public function connection(Job|Closure $job): string;
}