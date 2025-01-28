<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Libraries\Discount as Discount;
use App\Models\Discount as DiscountModel;
use App\Libraries\Response as Response;
class DiscountController extends Controller
{
    public function list(Request $request, $id)
    {
        $orders = Order::find($id)->toArray();

        if (!$orders) {
            return response()->json(['error' => 'Order not found']);
        }

        $discounts = DiscountModel::orderBy('order', 'asc')->get()->toArray();
        $discount = new Discount($orders, $discounts);

        return Response::responseJson(200, $discount->applyDiscount());



    }
}
