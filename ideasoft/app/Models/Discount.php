<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Discount extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'discounts';
    protected $table = 'discounts';
    protected $primaryKey = '_id';
}
