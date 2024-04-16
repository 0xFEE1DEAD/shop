<?php

namespace App\Services\Order\ReportsDataMap;

use App\Models\Order;
use App\Services\Reports\Interfaces\DataMapInterface;
use Generator;

class QuantityOrdersPerMonth implements DataMapInterface
{
    public function __construct(
        private Order $order
    ) {
    }

    public function getColumnNames(): array
    {
        return [
            'Дата',
            'Количество продаж'
        ];
    }

    public function getDataGenerator(): Generator
    {
        $quantityOrders = $this
            ->order
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->get();

        foreach ($quantityOrders as $order) {
            yield [
                $order->date,
                $order->count
            ];
        }
    }
}