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
        $date = new \DateTime();

        $orders = [
            [
                "customerId" => 1,
                "items" => [
                    [
                        "productId" => 102,
                        "quantity" => 10,
                        "unitPrice" => 11.28,
                        "total" => 112.80
                    ]
                ],
                "total" => 112.80,
                "created_at" => $date->format('Y-m-d\TH:i:s.vP'),
                "updated_at" => $date->format('Y-m-d\TH:i:s.vP')
            ],
            [
                "customerId" => 2,
                "items" => [
                    [
                        "productId" => 101,
                        "quantity" => 2,
                        "unitPrice" => 49.50,
                        "total" => 99.00
                    ],
                    [
                        "productId" => 100,
                        "quantity" => 1,
                        "unitPrice" => 120.75,
                        "total" => 120.75
                    ]
                ],
                "total" => 219.75,
                "created_at" => $date->format('Y-m-d\TH:i:s.vP'),
                "updated_at" => $date->format('Y-m-d\TH:i:s.vP')
            ],
            [
                "customerId" => 3,
                "items" => [
                    [
                        "productId" => 102,
                        "quantity" => 6,
                        "unitPrice" => 11.28,
                        "total" => 67.68
                    ],
                    [
                        "productId" => 100,
                        "quantity" => 10,
                        "unitPrice" => 120.75,
                        "total" => 1207.50
                    ]
                ],
                "total" => 1275.18,
                "created_at" => $date->format('Y-m-d\TH:i:s.vP'),
                "updated_at" => $date->format('Y-m-d\TH:i:s.vP')
            ]
        ];

        DB::connection(env('MONGO_DB_CONNECTION', 'localhost'))->getCollection('orders')->insertMany($orders);



    }
}
