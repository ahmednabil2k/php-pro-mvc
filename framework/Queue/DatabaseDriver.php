<?php

namespace Framework\Queue;

use Closure;
use Opis\Closure\SerializableClosure;

class DatabaseDriver implements QueueDriver
{
    protected array $config = [];
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function push(Job|Closure $job): int
    {
        $jobModel = new \Framework\Queue\Models\Job();

        $jobModel->connection = $this->connection($job);
        $jobModel->queue = $this->queue($job);
        $jobModel->payload = $this->serializeJob($job);
        $jobModel->attempts = 0;

        $jobModel->save();
        return $jobModel->id;

    }

    public function pull(): mixed
    {
        $attempts = config('queue.database.attempts');

        return \Framework\Queue\Models\Job::where('attempts', '<', $attempts)
            ->where('is_complete', '=', false)
            ->first();
    }

    public function connection(Job|Closure $job): string
    {
        if (is_callable($job))
            return $this->config['_connection'];

        return $job->getConnection();
    }

    public function queue(Job|Closure $job)
    {
        if (is_callable($job))
            return $this->config['queue'] ?? 'default';

        return $job->getQueue();
    }

    protected function table()
    {
        return $this->config['table'];
    }

    protected function attempts()
    {
        return $this->config['attempts'];
    }

    /**
     * @param Job|Closure $job
     * @return string
     */
    private function serializeJob(Job|Closure $job): string
    {
        if (is_callable($job)) {
            $wrapper = new SerializableClosure($job);
            return serialize($wrapper);
        }

        return serialize($job);
    }
}