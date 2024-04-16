<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderReportRequest;
use App\Services\Order\OrderService;

class OrderController extends Controller
{
    public function __construct(
        protected OrderService $orderService
    )
    {
    }
    public function getReport(OrderReportRequest $request) {
        $reportResult = $this->orderService->quantityOrdersPerMonth($request->get('type'));
        
        return response()->stream(function() use ($reportResult) {
                $reportResult->streamToClient();
            }, 
            200, 
            [
                'Content-Type' => match($request->get('type')) {
                    'csv' => 'text/csv',
                    'json' => 'application/json'
                }
            ]
        );
    }
}
