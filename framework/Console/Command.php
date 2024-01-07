<?php

namespace Framework\Console;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand
{
    private InputInterface $input;
    private OutputInterface $output;

    protected string $description;
    protected string $help;

    protected array $arguments = [];

    protected function configure()
    {
        $this
            ->setDescription($this->description ?? '')
            ->setHelp($this->help ?? '');

        foreach ($this->getArguments() as $argument => $options) {

            $mode = isset($options['required']) && $options['required'] ? InputArgument::REQUIRED : InputArgument::OPTIONAL;
            $description = isset($options['description']) ?? '';
            $default = isset($options['default']) ?? null;

            if ($mode === InputArgument::REQUIRED) {
                $this->addArgument($argument, $mode, $description);
            } else {
                $this->addArgument($argument, $mode, $description, $default);
            }
        }

    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        
        return $this->handle();
    }

    private function getArguments(): array
    {
        return $this->arguments;
    }

    public function getArgument(string $argument, mixed $default = null)
    {
        return $this->input->getArgument($argument) ?: $default;
    }

    public function getOption(string $option, mixed $default = null)
    {
        return $this->input->getOption($option) ?: $default;
    }

    public function info(string $message)
    {
        $this->output->writeln($message);
    }

    abstract public function handle(): int;
}