<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    public function test_category_tree_with_products_ok(): void
    {
        $response = $this->get('/category');

        $response->assertStatus(200);
    }

    public function test_category_order_up_works(): void
    {
        $categories = Category::factory()->count(10)->create();
        $categories = Category::where('parent_id', $categories->first()->parent_id)->inRandomOrder()->get();

        $response = $this->get(
            route('category.change.order.up', ['category'=> $categories->first()->id, 'by' => 3]),
        );

        $response->assertStatus(200);
    }

    public function test_category_order_down_works(): void
    {
        $categories = Category::factory()->count(10)->create();
        $categories = Category::where('parent_id', $categories->first()->parent_id)->inRandomOrder()->get();

        $response = $this->get(
            route('category.change.order.down', ['category'=> $categories->first()->id, 'by' => 3]),
        );

        $response->assertStatus(200);
    }
}
