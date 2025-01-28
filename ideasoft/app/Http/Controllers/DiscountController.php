<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Libraries\Discount as Discount;
use App\Models\Discount as DiscountModel;
use App\Libraries\Response as Response;
use Illuminate\Support\Facades\Redis;

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
