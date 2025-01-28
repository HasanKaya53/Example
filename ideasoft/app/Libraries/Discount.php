<?php

namespace App\Libraries;

use App\Models\Discount as DiscountModel;
use App\Models\Product;
class Discount
{
    private array $discounts = [];
    private array $order = [];
    private float $totalAmount = 0;
    private float $totalQuantity = 0;
    private float $totalDiscount = 0;
    private float $totalDiscountedAmount = 0;

    public function getTotalDiscountAmount(): float
    {
        return number_format($this->totalDiscountedAmount, 2, '.', '');
    }


    public function __construct($order, $discounts)
    {
        $this->order = $order;
        $this->totalAmount = 0;
        $this->totalQuantity = 0;
        $this->totalDiscount = 0;
        $this->totalDiscountedAmount = 0;
        $this->discounts = $discounts;


    }

    private function getItemDetails($item){
        $productId = $item['productId'];
        $product = Product::find($productId)->toArray();

        return [
            'productId' => $productId,
            'productCategory' => $product['category'],
            'productPrice' => $product['price'],
            'productQuantity' => $item['quantity'],
            'productTotalPrice' => $product['price'] * $item['quantity']
        ];

    }

    private function setResponse($reason, $totalAmount, $totalDiscount){
        return [
            'discountReason' => $reason,
            'discountAmount' => number_format($totalAmount, 2, '.', ''),
            'subtotal' => number_format($totalDiscount, 2, '.', '')
        ];
    }



    public function applyRule(){
        $order = $this->order;

        foreach ($order['items'] as $key => $item) {
            $itemDetails = $this->getItemDetails($item);
            $this->order['items'][$key]['category'] = $itemDetails['productCategory'];
        }

        $discountData = [];

        foreach ($this->discounts as $rule) {
          if ($rule['discount_type'] === 'free_item' && isset($rule['conditions']['minQuantity'])) {
                // 6 ürün al 1 bedava
                $categoryItems = array_filter( $this->order['items'] , fn($item) => $item['category'] == $rule['conditions']['category']);
                $totalQuantity = array_sum(array_column($categoryItems, 'quantity'));
                //tüm sepete bakıyorum ve kategorisi aynı olanları topluyorum.
                if ($totalQuantity >= $rule['conditions']['minQuantity']) {
                    //freeItems sayısı hesaplıyorum. Zaten toplam sayı 6 değilse 1 bedava yapacak kadar ürün yok. Fakat toplam sayı 6 ise bile siparişlerin içine bakmam gerekiyor.

                    $freeItems = intdiv($totalQuantity, $rule['conditions']['minQuantity']) * $rule['discount']['freeQuantity'];
                    //kategoriler arasında dolaşıp, ücretsiz adet kadar olanlardan ürünü ücretsiz yapıyorum.
                    if($freeItems > 0){
                        $freePrice = 0;
                        foreach($this->order['items'] as $itemKey => $itemValue){
                            if($itemValue['category'] == $rule['conditions']['category'] && $itemValue['quantity'] >= $rule['conditions']['minQuantity']){
                                $this->order['total'] -= $itemValue['unitPrice'];
                                $freePrice += $itemValue['unitPrice'];
                            }
                        }

                        $this->totalDiscountedAmount += $freePrice;
                        $discountData[] = $this->setResponse($rule['rule_name'], $freePrice ,$this->order['total']);
                    }

                }
            }if ($rule['discount_type'] === 'percentage' && isset($rule['discount']['applyTo']) && $rule['discount']['applyTo'] === 'cheapest') {
                // En ucuz ürüne %20 indirim
                $categoryItems = array_filter($this->order['items'], fn($item) => $item['category'] == $rule['conditions']['category']);

                $cheapestItem = 0;
                foreach($categoryItems as $itemKey => $itemValue){
                   if($itemValue['quantity'] >= $rule['conditions']['minQuantity']){
                       if($cheapestItem > $itemValue['unitPrice'] || $cheapestItem == 0){
                           $cheapestItem = $itemValue['unitPrice'];
                       }
                   }
                }

                if($cheapestItem > 0){
                   //%20 indirim yap..
                    $discountAmount = $cheapestItem * $rule['discount']['rate'] / 100;
                    $this->order['total'] -= $discountAmount;
                    $discountData[] = $this->setResponse($rule['rule_name'], $discountAmount ,$this->order['total']);

                    $this->totalDiscountedAmount += $discountAmount;
                }

            }  if ($rule['discount_type'] === 'percentage' && isset($rule['conditions']['minAmount'])) {
                // %10 toplam tutarda indirim
                if ($this->order['total'] >= $rule['conditions']['minAmount']) {
                    $discountAmount = $this->order['total'] * $rule['discount']['rate'] / 100;
                    $this->order['total'] = $this->order['total'] - $discountAmount;
                    $this->totalDiscountedAmount += $discountAmount;
                }

                $discountData[] = $this->setResponse($rule['rule_name'], $discountAmount ,$this->order['total']);
            }
        }


        return $discountData;


    }


    public function applyDiscount(){

        $discountData[] = $this->applyRule();
        return $discountData;
    }

}
