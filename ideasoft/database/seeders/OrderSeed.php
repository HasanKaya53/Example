<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class OrderSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                "id" => 1,
                "customerId" => 1,
                "items" => [
                    [
                        "productId" => 102,
                        "quantity" => 10,
                        "unitPrice" => "11.28",
                        "total" => "112.80"
                    ]
                ],
                "total" => "112.80"
            ],
            [
                "id" => 2,
                "customerId" => 2,
                "items" => [
                    [
                        "productId" => 101,
                        "quantity" => 2,
                        "unitPrice" => "49.50",
                        "total" => "99.00"
                    ],
                    [
                        "productId" => 100,
                        "quantity" => 1,
                        "unitPrice" => "120.75",
                        "total" => "120.75"
                    ]
                ],
                "total" => "219.75"
            ],
            [
                "id" => 3,
                "customerId" => 3,
                "items" => [
                    [
                        "productId" => 102,
                        "quantity" => 6,
                        "unitPrice" => "11.28",
                        "total" => "67.68"
                    ],
                    [
                        "productId" => 100,
                        "quantity" => 10,
                        "unitPrice" => "120.75",
                        "total" => "1207.50"
                    ]
                ],
                "total" => "1275.18"
            ]
        ];

        DB::connection(env('MONGO_DB_CONNECTION', 'localhost'))->getCollection('orders')->insertMany($orders);



    }
}
