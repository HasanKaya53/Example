<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'category',
        'price',
        'stock',
        'description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer'
    ];


    public static function updateOrCreateProduct($name, $category, $price, $stock)
    {
        return self::updateOrCreate(
            ['name' => $name, 'category' => $category], // Arama koşulu
            ['price' => $price, 'stock' => $stock] // Güncelleme veya ekleme yapılacak alanlar
        );
    }

}
