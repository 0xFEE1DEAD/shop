<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\Order\OrderService;
use App\Models\Order;
use App\Models\Category;
use App\Models\Product;

class OrderServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_report_quantity_orders_per_month(): void
    {
        $orderModel = new Order;
        $orderService = new OrderService($orderModel);
        $result = $orderService->quantityOrdersPerMonth('json');

        $ordersForCurrentMonth = $orderModel
            ->selectRaw('DATE(created_at) as date')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->groupBy('date')->map(function ($items) {
                return $items->count();
            });
        
        $resultAsArray = json_decode($result, true);
        $this->assertCount($ordersForCurrentMonth->count(), $resultAsArray);
        
        $resultCollection = collect($resultAsArray)->mapWithKeys(function($item) {
            $item = array_values($item);
            $keyindex = 0;
            $valindex = 1;
            if(gettype($item[0]) !== 'string') {
                $keyindex = 1;
                $valindex = 0;
            }
            return [$item[$keyindex] => $item[$valindex]];
        });

        $this->assertEmpty($ordersForCurrentMonth->diff($resultCollection));
    }
}
