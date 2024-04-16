<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Order;

class OrderTest extends TestCase
{
    public function test_report_csv_works(): void
    {
        $response = $this->get(
            route('order.report', ['type' => 'csv']),
        );

        $response->assertStatus(200);
    }

    public function test_report_json_works(): void
    {
        $response = $this->get(
            route('order.report', ['type' => 'json']),
        );

        $response->assertStatus(200);
    }
}
