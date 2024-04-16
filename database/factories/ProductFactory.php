<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    private const PRODUCT_ELEMENTS = [
        "Космический бриллиант",
        "Экстравагантный кофе",
        "Шоколадное волшебство",
        "Лунный сияние",
        "Фруктовый экстаз",
        "Золотой ветер",
        "Радужная ракета",
        "Морской призрак",
        "Солнечный удар",
        "Лавандовый вихрь",
        "Лесная магия",
        "Яркая эйфория",
        "Весенний туман",
        "Огненный танец",
        "Лимонная свежесть",
        "Звездное путешествие",
        "Пираньи горизонта",
        "Серебряный океан",
        "Мраморное сокровище",
        "Ледяной коктейль"
    ];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(self::PRODUCT_ELEMENTS),
            'price' => $this->faker->randomFloat(2, 0, 999999),
        ];
    }
}
