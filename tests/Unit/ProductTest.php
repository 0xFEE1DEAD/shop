<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use App\Exceptions\HasOrdering\OrderingColumnChangeNotAllowed;
use Tests\TestCase;
use App\Models\Product;

class ProductTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_have_category_and_category_have_product(): void
    {
        $product = Product::inRandomOrder()->first();
        $category = $product->category;
        $this->assertTrue($category->products->contains($product));
    }

    public function test_product_order_values_creates_sequentially()
    {
        $product = Product::inRandomOrder()->first();
        $category = $product->category;

        Product::factory()->count(10)->create([
            'category_id' => $category->id
        ]);

        $products = $category->products;

        $this->assertArrayValuesSequentially($products->pluck('order')->sort()->all());
    }

    public function test_product_order_changing_directly_exception()
    {
        $this->expectException(OrderingColumnChangeNotAllowed::class);
        $product = Product::inRandomOrder()->first();
        $product->order = 99;
        $product->save();
    }

    public function test_product_order_up_from_1_by_1(): void
    {
        $this->orderTesting(
            startPosition: 1,
            by: 1,
            needToChange: 0,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_product_order_up_from_1_by_2(): void
    {
        $this->orderTesting(
            startPosition: 1,
            by: 2,
            needToChange: 0,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_product_order_up_from_2_by_1(): void
    {
        $this->orderTesting(
            startPosition: 2,
            by: 1,
            needToChange: -1,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_product_order_up_from_2_by_2(): void
    {
        $this->orderTesting(
            startPosition: 2,
            by: 2,
            needToChange: -1,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_product_order_up_from_2_by_100(): void
    {
        $this->orderTesting(
            startPosition: 2,
            by: 100,
            needToChange: -1,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_product_order_up_from_7_by_3(): void
    {
        $this->orderTesting(
            startPosition: 7,
            by: 3,
            needToChange: -3,
            functionName: 'changeOrderUpBy'
        );
    }

    public function test_product_order_down_from_last_by_1(): void
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

    public function test_product_order_down_from_5_by_1(): void
    {
        $this->orderTesting(
            startPosition: 5,
            by: 1,
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
        $product = Product::inRandomOrder()->first();
        $category = $product->category;

        Product::factory()->count(10)->create([
            'category_id' => $category->id
        ]);

        $products = $category->products;

        $product = $this->getProductFromProductsWithOrder($products, $startPosition);

        $oldOrdering = $product->order;
        $product->$functionName($by);

        $category->load('products');
        $products = $category->products;

        $this->assertArrayValuesSequentially($products->pluck('order')->sort()->all());

        $orderedProduct = $products->firstWhere('id', $product->id);

        $this->assertEquals($needToChange, $orderedProduct->order - $oldOrdering);
    }

    private function getProductFromProductsWithOrder(Collection $products, int $order): ?Product
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
