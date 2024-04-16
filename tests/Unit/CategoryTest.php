<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryTest extends TestCase
{
    public function test_exists_category_with_products_and_product_have_that_category(): void
    {
        $category = Category::whereHas('products')->with('products')->inRandomOrder()->first();
        $product = $category->products->first();

        $this->assertFalse(is_null($product));
        $this->assertEquals($product->category_id, $category->id);
    }

    public function test_has_children_and_children_has_parent(): void
    {
        $category = Category::whereHas('children')
            ->with('children', function($q) {$q->with('parent');})
            ->inRandomOrder()
            ->first();
        $child = $category->children->first();
        $this->assertEquals($child->parent->id, $category->id);
    }

    public function test_category_order_up_from_1_by_1(): void
    {
        $this->orderTesting(
            startPosition: 1,
            by: 1,
            needToChange: 0,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_category_order_up_from_1_by_2(): void
    {
        $this->orderTesting(
            startPosition: 1,
            by: 2,
            needToChange: 0,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_category_order_up_from_2_by_1(): void
    {
        $this->orderTesting(
            startPosition: 2,
            by: 1,
            needToChange: -1,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_category_order_up_from_2_by_2(): void
    {
        $this->orderTesting(
            startPosition: 2,
            by: 2,
            needToChange: -1,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_category_order_up_from_5_by_1(): void
    {
        $this->orderTesting(
            startPosition: 5,
            by: 1,
            needToChange: -1,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_category_order_up_from_4_by_3(): void
    {
        $this->orderTesting(
            startPosition: 4,
            by: 3,
            needToChange: -3,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_category_order_down_from_last_by_1(): void
    {
        $this->orderTesting(
            startPosition: -1,
            by: 1,
            needToChange: 0,
            functionName: 'changeOrderDownBy'
        );
    }

    public function test_product_order_down_from_last_by_2(): void
    {
        $this->orderTesting(
            startPosition: -1,
            by: 2,
            needToChange: 0,
            functionName: 'changeOrderDownBy'
        );
    }

    public function test_product_order_down_from_prelast_by_1(): void
    {
        $this->orderTesting(
            startPosition: -2,
            by: 1,
            needToChange: 1,
            functionName: 'changeOrderDownBy'
        );
    }

    public function test_product_order_down_from_prelast_by_2(): void
    {
        $this->orderTesting(
            startPosition: -2,
            by: 2,
            needToChange: 1,
            functionName: 'changeOrderDownBy'
        );
    }

    public function test_product_order_down_from_5_by_3(): void
    {
        $this->orderTesting(
            startPosition: 5,
            by: 3,
            needToChange: 3,
            functionName: 'changeOrderDownBy'
        );
    }

    private function orderTesting(int $startPosition, int $by, int $needToChange, string $functionName) {
        $categories = Category::factory()->count(10)->create();
        $categories = Category::where('parent_id', $categories->first()->parent_id)->get();

        $category = $this->getCategoryFromCollectionWithOrder($categories, $startPosition);

        $oldOrdering = $category->order;
        $category->$functionName($by);

        $categories = Category::where('parent_id', $category->parent_id)->get();

        $this->assertArrayValuesSequentially($categories->pluck('order')->sort()->all());

        $orderedCategory = $categories->firstWhere('id', $category->id);

        $this->assertEquals($needToChange, $orderedCategory->order - $oldOrdering);
    }

    private function getCategoryFromCollectionWithOrder(Collection $products, int $order): ?Category
    {
        $maxOrder = 0;
        $maxOrderProduct = null;

        if ($order < 0) {
            $maxOrder = $products->pluck('order')->max();
            $order = $maxOrder + $order + 1;
        }

        foreach($products as $_product) {
            if($_product->order === $order) {
                return $_product;
            }
        }

        return $maxOrderProduct;
    }
}
