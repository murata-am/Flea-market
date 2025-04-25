<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoldItemFactory extends Factory
{
    public function definition()
    {
        return [
            'buyer_id' => User::factory(),
            'item_id' => Item::factory(),
            'shipping_address' => $this->faker->address(),
            'payment_method' => $this->faker->randomElement([
                'コンビニ支払い', 'カード支払い',
            ]),
        ];
    }
}
