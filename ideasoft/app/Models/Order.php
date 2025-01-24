<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';
    protected $table = 'orders';
    protected $fillable = ['id', 'customerId', 'items', 'total'];
    protected $primaryKey = '_id';


}
