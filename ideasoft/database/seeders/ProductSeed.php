<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 100,
                'name' => 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti',
                'category' => 1,
                'price' => 120.75,
                'stock' => 10
            ],
            [
                'id' => 101,
                'name' => 'Reko Mini Tamir Hassas Tornavida Seti 32\'li',
                'category' => 1,
                'price' => 49.50,
                'stock' => 10
            ],
            [
                'id' => 102,
                'name' => 'Viko Karre Anahtar - Beyaz',
                'category' => 2,
                'price' => 11.28,
                'stock' => 10
            ],
            [
                'id' => 103,
                'name' => 'Legrand Salbei Anahtar, Alüminyum',
                'category' => 2,
                'price' => 22.80,
                'stock' => 10
            ],
            [
                'id' => 104,
                'name' => 'Schneider Asfora Beyaz Komütatör',
                'category' => 2,
                'price' => 12.95,
                'stock' => 10
            ],
        ];

        // Verileri veritabanına ekleyin
        foreach ($products as $product) {
            Product::updateOrCreate(
                ['id' => $product['id']], // Aynı id'ye sahip bir ürün varsa güncelle
                $product
            );
        }
    }
}
