<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->words(4, true),
            'price' => $this->faker->numberBetween(10.00, 9999.99),
            'url' => $this->faker->url(),
            'description' => $this->faker->paragraphs(5, true),
        ];
    }

    public function amazon(): self
    {
        return $this->state(function () {
            return [
                'url' => 'https://amazon.com/' . $this->faker->numberBetween(1000, 999999),
            ];
        });
    }

    public function steam(): self
    {
        return $this->state(function () {
            return [
                'url' => 'https://steampowered.com/app/' . $this->faker->numberBetween(1000, 999999),
            ];
        });
    }

    public function zid(): self
    {
        return $this->state(function () {
            return [
                'url' => 'https://zid.store/products/' . $this->faker->uuid(),
            ];
        });
    }
}
