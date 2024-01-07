<?php

namespace App\Models;

use Framework\Database\Model;

class User extends Model
{
    protected string $table = 'users';

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}