<?php

namespace Framework\Contracts\Kernel;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ConsoleKernel
{
    public function bootstrap();

    /**
     * Handle an incoming console command.
     *
     * @param InputInterface $input
     * @param OutputInterface|null $output
     * @return int
     */
    public function handle(InputInterface $input, OutputInterface $output = null): int;

    public function getApplication();
}