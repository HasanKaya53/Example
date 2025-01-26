<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Libraries\Response as Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;
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

            return Response::responseJson(200, $order);
        } catch (\Exception $e) {
            return Response::responseJson( 500, [], "An error occurred while fetching orders");
        }
    }

    public function addOrder(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'customerId' => 'required|exists:customers,id', // Müşterinin varlığını kontrol et
            'items' => 'required|array', // Items alanının bir dizi olması gerektiğini belirtiyoruz
            'items.*.productId' => 'required|exists:products,id', // Her bir ürün ID'sinin geçerli bir ürün olduğunu kontrol et
            'items.*.quantity' => 'required|integer|min:1', // Her ürün için geçerli bir miktar belirtilmesi gerektiğini kontrol et
        ]);

        // Doğrulama hataları varsa, hata mesajını döndür
        if ($validator->fails()) {
            return Response::responseJson(400,[],  $validator->errors()->first());
        }



        $total = 0;
        $items = [];
        foreach ($request->items as $item) {
            $product = Product::find($item['productId']);
            $total += $product->price * $item['quantity'];
            $items[] = [
                'productId' => $item['productId'],
                'quantity' => $item['quantity'],
                'unitPrice' => floatval($product->price),
                'total' => floatval($product->price) * $item['quantity']
            ];
        }
        $insertData = [
            'customerId' => $request->customerId,
            'items' => $items,
            'total' => $total
        ];
        $order = Order::create($insertData);
        if (!$order) {
            return Response::responseJson(500, [], "An error occurred while adding order");
        }

        return Response::responseJson(200, 'Order added successfully');
    }
}
