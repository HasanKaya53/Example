<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'since',
        'revenue'
    ];


    public static function updateOrCreateCustomer($name, $since, $revenue)
    {
        return self::updateOrCreate(
            ['name' => $name], // Arama koşulu
            ['since' => $since, 'revenue' => $revenue] // Güncelleme veya ekleme yapılacak alanlar
        );
    }
}
