<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
    public function getOrders(Request $request): \Illuminate\Http\JsonResponse
    {

        try {
            $orders = Order::all(['id','customerId', 'items', 'total']);
            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred while fetching orders"], 500);
        }
    }


    public function getOrder(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (empty($id)) {
            return response()->json(['error' => "Order id is required"], 400);
        }

        try {
            $order = Order::where('_id', $id)->first();
            return response()->json($order);
        } catch (\Exception $e) {
            return response()->json(['error' => "An error occurred while fetching order"], 500);
        }
    }
}
