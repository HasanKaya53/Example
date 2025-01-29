<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Libraries\Discount as Discount;
use App\Models\Discount as DiscountModel;
use App\Libraries\Response as Response;
use Illuminate\Support\Facades\Redis;

/**
 * @OA\Post(
 * path="/discount/list/{id}",
 * summary="Siparişe uygulanan indirimleri listele",
 * operationId="getDiscountsByOrderId",
 * tags={"İndirimler"},
 * security={{"basicAuth": {}}},
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="Order ID",
 * @OA\Schema(type="string", example="6796d41193dc87a73d011182")
 * ),
 * @OA\Response(
 * response=200,
 * description="Indirimler başarıyla getirildi",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="orderId", type="string", example="6796d41193dc87a73d011182"),
 * @OA\Property(property="totalAmount", type="number", format="float", example=122.95),
 * @OA\Property(
 * property="discounts",
 * type="array",
 * @OA\Items(
 * type="object",
 * @OA\Property(property="discountReason", type="string", example="6 ürün al 1 bedava"),
 * @OA\Property(property="discountAmount", type="string", example="11.28"),
 * @OA\Property(property="subtotal", type="string", example="1116.72")
 * )
 * ),
 * @OA\Property(property="discountedTotal", type="string", example="1005.05")
 * )
 * ),
 * @OA\Response(
 * response=404,
 * description="Sipariş bulunamadı",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="status", type="boolean", example=false),
 * @OA\Property(property="message", type="string", example="Sipariş bulunamadı")
 * )
 * ),
 * @OA\Response(
 * response=400,
 * description="Geçersiz İstek",
 * @OA\JsonContent(
 * type="object",
 * @OA\Property(property="status", type="boolean", example=false),
 * @OA\Property(property="message", type="string", example="Geçersiz istek")
 * )
 * )
 * )
 *
 */

class DiscountController extends Controller
{
    public function list(Request $request, $id)
    {

        $orders = Order::find($id)->toArray();

        if (!$orders) {
            return Response::responseJson(404, [], 'Order not found');
        }

        $cachedData = Redis::get('order_'.$id);

        if ($cachedData) {
            return Response::responseJson(200, json_decode($cachedData), null, '');
        }



        $discounts = DiscountModel::orderBy('order', 'asc')->get()->toArray();
        $discount = new Discount($orders, $discounts);


        $discountData = $discount->applyDiscount();


        if (empty($discountData)) {
            return Response::responseJson(200, [], 'No discounts applied');
        }
        $discountData = $discountData[0];
        $lastItem = end($discountData);
        $lastSubtotal = $lastItem['subtotal'];

        $returnData =[
            'orderId' => $id,
            'totalAmount' => $discount->getTotalDiscountAmount(),
            'discounts' => $discountData,
            'discountedTotal' => number_format(floatval($lastSubtotal), 2, '.', '')
        ];

        Redis::set('order_'.$id, json_encode($returnData));

        return Response::responseJson(200,$returnData , null, '');



    }
}
