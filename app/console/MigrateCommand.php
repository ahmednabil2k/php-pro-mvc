<?php

namespace App\Console;

use Framework\Console\Command;
use Framework\Database\Exceptions\QueryException;
use Framework\Database\Factory;
use Framework\Database\Schema\Schema;
use Framework\Database\Schema\SchemaBuilder;
use Symfony\Component\Console\Input\InputOption;

class MigrateCommand extends Command
{
    protected static $defaultName = 'migrate';
    protected string $description = 'migrate tables to database';
    protected string $help = 'migrate tables to database from a location or by passing a specific classname';

    protected array $arguments = [
        'class' => [
            'required' => false,
            'description' => 'the classname to be migrated to database'
        ]
    ];

    protected function configure()
    {
        parent::configure();
        $this->addOption('fresh', null, InputOption::VALUE_NONE, 'Delete all tables before running the migrations');
    }

    /**
     * @throws QueryException
     */
    public function handle(): int
    {
        $pattern = basePath() . '/database/migrations/*.php';
        $paths = glob("{$pattern}");

        if (count($paths) === 0) {
            $this->info('No migrations found');
            return 0;
        }

        $connection = app('db.default');

        if ($fresh = $this->getOption('fresh')) {
            $this->info('dropping database tables....');
            $connection->dropTables();
            $this->info('dropped database tables successfully');
        }

        if (!$connection->hasTable('migrations')) {
            $this->info('Creating migrations table');
            $this->createMigrationsTable();
            $this->info('Created migrations table successfully');

        }

        $migrated = [];

        if (!$fresh) {

            $migrations = $connection
                ->query()
                ->from('migrations')
                ->all('name');

            foreach ($migrations as $migration) {
                $migrated[] = $migration['name'];
            }
        }

        foreach ($paths as $path) {

            $pathParts = explode("\\", realpath($path));
            $name = $pathParts[count($pathParts) - 1];

            if (in_array($name, $migrated)) {
                continue;
            }

            $class = require_once $path;
            $class->run();

            $connection
                ->query()
                ->from('migrations')
                ->insert(['name' => $name]);

            $this->info("Created migration {$name} \n");
        }

        return 0;
    }

    private function createMigrationsTable()
    {
        Schema::create('migrations', function (SchemaBuilder $builder) {
            $builder->id('id')->primary();
            $builder->string('name');
        });
    }

}