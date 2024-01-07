<?php

namespace Framework\Queue;
abstract class Job
{
    protected int $maxAttempts = 3;

    protected int $timeOut = 30;

    protected ?string $connection = null;

    protected ?string $queue = null;

    /**
     * @return int
     */
    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    /**
     * @return int
     */
    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function onConnection(string $name): static
    {
        $this->connection = $name;
        return $this;
    }

    public function getConnection()
    {
        return $this->connection ?? app('queue')->connection();
    }

    /**
     * @param string $name
     * @return $this
     */
    public function onQueue(string $name): static
    {
        $this->queue = $name;
        return $this;
    }

    public function getQueue()
    {
        return $this->queue ?? app('queue')->queue();
    }

    /**
     * @return void
     */
    public function dispatch(): int
    {
        $queue = $this->connection ? app(QueueManager::class)->connect($this->connection) : app('queue');

        return $queue->push($this);
    }

    /**
     * @return void
     */
    abstract public function handle(): void;
}