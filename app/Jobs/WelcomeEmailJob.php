<?php

namespace App\Jobs;

use Framework\Queue\Job;
class WelcomeEmailJob extends Job
{

    public function handle(): void
    {
        sleep(2);
        echo "hello world\n";
    }
}