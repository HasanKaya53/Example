<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $collection = 'users';
    protected $fillable = ['name', 'email', 'password'];
}
