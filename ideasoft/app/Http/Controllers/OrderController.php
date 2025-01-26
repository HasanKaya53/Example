<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Libraries\Response as Response;
use App\Models\Customer;

class OrderController extends Controller
{
    //
    private function mapOrder($orders): object
    {
        $orders = $orders->map(function ($order) {
            $customer = Customer::where('id', $order->customerId)->first();
            $order->customer = $customer->name;
            return $order;
        });

        return $orders;
    }

    public function getOrders(Request $request): \Illuminate\Http\JsonResponse
    {

        try {

            $orders = Order::all(['_id', 'customerId', 'items', 'total']);
            $orders = $this->mapOrder($orders);

            return Response::responseJson(200,$orders);
        } catch (\Exception $e) {
            return Response::responseJson( 500, [], "An error occurred while fetching orders");
        }
    }


    public function getOrder(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (empty($id)) {
            return Response::responseJson(400, [], "Order id is required");
        }

        try {
            $order = Order::find($id, ['_id', 'customerId', 'items', 'total']);
            $order = $this->mapOrder(collect([$order]))->first();

            return response()->json($order);
        } catch (\Exception $e) {
            return Response::responseJson( 500, [], "An error occurred while fetching orders");
        }
    }
}
