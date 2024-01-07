<?php

namespace App\Console;

use Exception;
use Framework\Console\Command;
use Framework\Queue\Models\Job as JobModel;

class QueueWorkCommand extends Command
{
    protected static $defaultName = 'queue:work';

    protected string $description = 'spin up the work command to process background jobs';

    protected string $help = '';

    protected array $arguments = [];

    public function handle(): int
    {
        try {

            $this->info('Waiting for jobs on queue: ' . app('queue')->queue() . "\n");

            while (true) {

                $job = app('queue')->pull();
                if (!$job) {
                    sleep(5);
                    continue;
                }

                if ($job instanceof JobModel) {

                    $queuedJob = unserialize($job->payload);

                    $class = get_class($queuedJob);
                    $this->info("Starting processing job: {$class}\n");

                    app()->call([$queuedJob, 'handle']);
                    $job->delete();

                    $this->info("Finished processing job: {$class}\n");
                }

                if (is_callable($job)) {
                    $this->info("Starting processing closure\n");
                    $job();
                    $this->info("Finished processing closure\n");
                }

                sleep(1);
            }

            return 0;

        } catch (Exception $e) {

            $this->info($e->getMessage());
            return 1;
        }

    }
}