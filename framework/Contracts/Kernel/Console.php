<?php

namespace Framework\Contracts\Kernel;

use Exception;
use Framework\Contracts\Http\App;
use Framework\Contracts\Pipeline;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Console implements ConsoleKernel
{
    public function __construct(protected App $app){}

    /**
     * @throws \Throwable
     */
    public function bootstrap()
    {
        $this->app->bootstrap();
    }

    /**
     * @throws \Throwable
     */
    public function handle(InputInterface $input, OutputInterface $output = null): int
    {
        $this->bootstrap();

        return (new Pipeline($this->app))
            ->then($this->executeCommand($input, $output));
    }

    private function executeCommand(InputInterface $input, OutputInterface $output = null): \Closure
    {
        return function () use ($input, $output) {
            $application = new Application();
            $commands = require basePath() . '/app/console/commands.php';

            foreach ($commands as $command) {
                $application->add(new $command());
            }

            try {
                return $application->run($input, $output);
            } catch (Exception $e) {
                return 1;
            }
        };
    }

    public function getApplication(): App
    {
        return $this->app;
    }
}