<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductTest extends TestCase
{
    public function test_category_order_up_works(): void
    {
        $category = Category::first();
        $products = Product::factory()->count(10)->create([
            'category_id' => $category->id
        ]);

        $response = $this->get(
            route('product.change.order.up', ['product'=> $products->first()->id, 'by' => 3]),
        );

        $response->assertStatus(200);
    }

    public function test_category_order_down_works(): void
    {
        $category = Category::first();
        $products = Product::factory()->count(10)->create([
            'category_id' => $category->id
        ]);

        $response = $this->get(
            route('product.change.order.down', ['product'=> $products->first()->id, 'by' => 3]),
        );

        $response->assertStatus(200);
    }
}
