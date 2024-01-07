<?php

namespace Framework\Queue\Models;

use Framework\Database\Model;

class Job extends Model
{

    /**
     * @return string
     */
    public function table(): string
    {
        return config('queue.database.table');
    }

    public function run()
    {
        app()->call([$this, 'handle']);
    }
}