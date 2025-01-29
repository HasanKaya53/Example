<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Libraries\Response as Response;
use Illuminate\Support\Facades\Validator;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;

/**
 * @OA\Get(
 *     path="/get/orders",
 *     summary="Tüm Siparişleri listele",
 *     tags={"Siparişler"},
 *     security={{"basicAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Başarılı",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Order")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Geçersiz İstek"
 *     )
 * )
 * @OA\Get(
 *     path="/get/order/{id}",
 *     summary="ID'ye göre sipariş getir",
 *     operationId="getOrderById",
 *     tags={"Siparişler"},
 *     security={{"basicAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Order ID",
 *         @OA\Schema(type="string", example="6796d41193dc87a73d011182")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order data retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="customerId", type="integer", example=1),
 *                 @OA\Property(property="customer", type="string", example="Türker Jöntürk"),
 *                 @OA\Property(property="id", type="string", example="6796d41193dc87a73d011182"),
 *                 @OA\Property(
 *                     property="items",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="productId", type="integer", example=102),
 *                         @OA\Property(property="quantity", type="integer", example=100),
 *                         @OA\Property(property="unitPrice", type="number", format="float", example=11.28),
 *                         @OA\Property(property="total", type="number", format="float", example=1128)
 *                     )
 *                 ),
 *                 @OA\Property(property="total", type="number", format="float", example=1128)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Order not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Order not found")
 *         )
 *     )
 * )
 * @OA\Post(
 *      path="/add/order",
 *      summary="Add a new Order",
 *      operationId="addOrder",
 *      tags={"Siparişler"},
 *      security={{"basicAuth": {}}},
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              type="object",
 *              required={"customerId", "items"},
 *              @OA\Property(property="customerId", type="integer", example=1),
 *              @OA\Property(
 *                  property="items",
 *                  type="array",
 *                  @OA\Items(
 *                      type="object",
 *                      required={"productId", "quantity"},
 *                      @OA\Property(property="productId", type="integer", example=102),
 *                      @OA\Property(property="quantity", type="integer", example=1)
 *                  )
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Order created successfully",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="status", type="boolean", example=true),
 *              @OA\Property(property="message", type="string", example="Order created successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="status", type="boolean", example=false),
 *              @OA\Property(property="message", type="string", example="Invalid input data")
 *          )
 *      )
 *  )
 * @OA\Post(
 *     path="/delete/order/{id}",
 *     summary="ID'ye göre sipariş sil",
 *     operationId="deleteOrderById",
 *     tags={"Siparişler"},
 *     security={{"basicAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Order ID",
 *         @OA\Schema(type="string", example="6796b49298894574290918b3")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Order deleted successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Order not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Order not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Invalid input data")
 *         )
 *     )
 * )
 */




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
