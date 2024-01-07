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
        Schema::create('orders', function (SchemaBuilder $builder) {
            $builder->id('id')->primary();
            $builder->id('user_id');
            $builder->float('amount_due');
            $builder->string('status');
            $builder->dateTime('created_at')->default(DateTimeField::CURRENT_TIMESTAMP);
        });
    }

    public function down()
    {
        // TODO: Implement down() method.
    }
};