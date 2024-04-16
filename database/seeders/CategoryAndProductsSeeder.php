<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class CategoryAndProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate();
        Product::truncate();

        for( $i = 0; $i < 5; $i++ ) {
            Category::factory()
                ->has(
                    $this->generateRandomCategoryFactory(
                        random_int(3, 8),
                        random_int(0, 25),
                        random_int(1, 4)
                    ),
                    'children'
                )
                ->create();
        }
    }

    public function generateRandomCategoryFactory(int $maxDepth, int $maxProducts, int $count): CategoryFactory {
        $factory = Category::factory();

        if ($maxProducts) {
            $factory = $factory->has(
                Product::factory()->count(
                    random_int(1, $maxProducts)
                )
            );
        }

        if ($maxDepth) {
            $factory = $factory->has(
                $this->generateRandomCategoryFactory(
                    random_int(0, $maxDepth - 1),
                    random_int(0, $maxProducts),
                    random_int(1, $count)
                ),
                'children'
            );
        }

        return $factory->count(random_int(1, $count));
    }
}
