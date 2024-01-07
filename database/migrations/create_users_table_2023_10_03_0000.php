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
        Schema::create('users', function (SchemaBuilder $builder) {
            $builder->id('id')->primary();
            $builder->string('name', 50);
            $builder->string('username', 50);
            $builder->string('email', 100)->nullable();
            $builder->dateTime('created_at')->default(DateTimeField::CURRENT_TIMESTAMP);
        });
    }

    public function down()
    {
        // TODO: Implement down() method.
    }
};