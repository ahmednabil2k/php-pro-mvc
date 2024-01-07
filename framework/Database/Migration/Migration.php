<?php

namespace Framework\Database\Migration;
abstract class Migration
{
    abstract public function run();
    abstract public function down();
}