<?php

namespace Database\Migrations;

use App\Models\Product;
use Framework\Database\Migration\Migration;
use Framework\Database\Schema\Field\DateTimeField;
use Framework\Database\Schema\Schema;
use Framework\Database\Schema\SchemaBuilder;

return new Class extends Migration
{
    public function run()
    {

        $products = [

            [
                'name' => 'Apple 15 Pro',
                'description' => 'Apple phone 2023 model',
                'status' => 'available',
            ],
            [
                'name' => 'FIFA 24',
                'description' => 'EA game 2023 model',
                'status' => 'checking',
            ],
            [
                'name' => 'PES 2023',
                'description' => 'Konami game 2023 model',
                'status' => 'pending',
            ],
        ];


        foreach ($products as $product) {
            Product::insert($product);
        }
    }

    public function down()
    {
        // TODO: Implement down() method.
    }
};