<?php

namespace Database\Migrations;

use Framework\Database\Migration\Migration;
use Framework\Database\Schema\Field\DateTimeField;
use Framework\Database\Schema\Schema;
use Framework\Database\Schema\SchemaBuilder;

return new Class extends Migration
{
    public function run()
    {
        Schema::create('jobs', function (SchemaBuilder $builder) {
            $builder->id('id')->primary();
            $builder->string('connection', 50);
            $builder->string('queue', 50)->default('default');
            $builder->text('payload');
            $builder->text('params')->nullable();
            $builder->int('attempts')->default(0);
            $builder->bool('is_complete')->default(false);
            $builder->dateTime('created_at')->default(DateTimeField::CURRENT_TIMESTAMP);
        });
    }

    public function down()
    {
        // TODO: Implement down() method.
    }
};