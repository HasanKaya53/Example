<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order Model",
 *     required={"customerId", "items", "total", "created_at", "updated_at"},
 *     @OA\Property(property="orderId", type="string", example="6796d41193dc87a73d011182"),
 *     @OA\Property(property="customerId", type="integer", example=1),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             required={"productId", "quantity", "unitPrice", "total"},
 *             @OA\Property(property="productId", type="integer", example=102),
 *             @OA\Property(property="quantity", type="integer", example=100),
 *             @OA\Property(property="unitPrice", type="number", format="float", example=11.28),
 *             @OA\Property(property="total", type="number", format="float", example=1128)
 *         )
 *     ),
 *     @OA\Property(property="total", type="number", format="float", example=1128),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-27T00:32:17.042+00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-27T00:32:17.042+00:00")
 * )
 */


class Discount extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'discounts';
    protected $table = 'discounts';
    protected $primaryKey = '_id';
}
