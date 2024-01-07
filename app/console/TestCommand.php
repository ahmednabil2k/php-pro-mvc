<?php

namespace App\Console;

use Framework\Console\Command;

class TestCommand extends Command
{
    protected static $defaultName = 'hello';

    protected string $description = 'Welcome username';

    protected string $help = 'Welcoming new user by printing the name';

    protected array $arguments = [
        'name' => [
            'required' => true,
            'description' => 'The username of the vendor'
        ]
    ];

    public function handle(): int
    {
        $name = $this->getArgument('name');
        $this->info(strtoupper($name));

        return 0;
    }
}