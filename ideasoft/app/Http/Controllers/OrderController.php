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
            $stock = $product->stock - $item['quantity'] - ($items[$item['productId']]['quantity'] ?? 0);
            if ($stock < 0) {
                return Response::responseJson(400, [], "There is not enough stock for the product with name: " . $product->name);
            }

            // Ürün miktarını ve toplam fiyatı hesapla
            $quantity = intval($item['quantity']) + ($items[$item['productId']]['quantity'] ?? 0);
            $total += $product->price * $quantity;
            $productPrice = floatval($product->price);

            //dizi olarak verileri tutuyorum.
            $items[$item['productId']] = [
                'productId' => $item['productId'],
                'quantity' => $quantity,
                'unitPrice' => $productPrice,
                'total' => floatval(number_format($productPrice * $quantity, 2, '.', '')),
                'stock' => $stock
            ];
        }

        // Stokları güncelle
        foreach ($items as $stock) {
            Product::where('id', $stock['productId'])->update(['stock' => $stock['stock']]);
        }
        $insertData = [
            'customerId' => $request->customerId,
            'items' => $items,
            'total' => floatval(number_format($total, 2, '.', ''))
        ];
        $order = Order::create($insertData);
        if (!$order) {
            return Response::responseJson(500, [], "An error occurred while adding order");
        }

        return Response::responseJson(200, 'Order added successfully');
    }

    public function deleteOrder(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (empty($id)) {
            return Response::responseJson(400, [], "Order id is required");
        }

        try {
            $order = Order::find($id);
            if (!$order) {
                return Response::responseJson(404, [], "Order not found");
            }
            $order->delete();
            return Response::responseJson(200, 'Order deleted successfully');
        } catch (\Exception $e) {
            return Response::responseJson( 500, [], "An error occurred while deleting order");
        }
    }
}
