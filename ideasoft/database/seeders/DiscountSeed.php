<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DiscountSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts =  [
            [
                "rule_name" => "%10 toplam tutarda indirim",
                "discount_type" => "percentage",
                "conditions" => [
                    "minAmount" => 1000
                ],
                "discount" => [
                    "rate" => 10
                ],
                "order" => "3"
            ],
            [
                "rule_name" => "6 ürün al 1 bedava",
                "discount_type" => "free_item",
                "conditions" => [
                    "minQuantity" => 6,
                    "category" => 2
                ],
                "discount" => [
                    "freeQuantity" => 1
                ],
                "order" => "2"
            ],
            [
                "rule_name" => "2 ürün al, en ucuzuna %20 indirim",
                "discount_type" => "percentage",
                "conditions" => [
                    "minQuantity" => 2,
                    "category" => 1
                ],
                "discount" => [
                    "rate" => 20,
                    "applyTo" => "cheapest"
                ],
                "order" => "1"
            ]
        ];


        DB::connection(env('MONGO_DB_CONNECTION', 'localhost'))->getCollection('discounts')->insertMany($discounts);
    }
}
