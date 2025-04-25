<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        $conditions = ['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'];
        shuffle($conditions);

        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'image' => $this->faker->imageUrl(),
            'price' => $this->faker->numberBetween(100, 5000),
            'description' => $this->faker->sentence(),
            'condition' => $conditions[0],
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Item $item) {
            $categoryIds = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $item->categories()->attach($categoryIds);
        });
    }
}
