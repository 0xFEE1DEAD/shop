<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\Reports\ReportService;
use App\Services\Order\ReportsDataMap\QuantityOrdersPerMonth;
use App\Services\Reports\Enums\ReportTypes;
use App\Services\Reports\ReportResult;

class OrderService
{
    public function __construct(
        protected Order $order,
    )
    {
    }

    public function quantityOrdersPerMonth(string $type): ReportResult
    {
        $reportService = new ReportService(
            new QuantityOrdersPerMonth($this->order)
        );

        return $reportService->getByType(ReportTypes::from($type));
    }
}