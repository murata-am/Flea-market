<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Comment;
use App\Models\User;
use App\Models\Item;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'content' => $this->faker->realText(50),
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
        ];
    }
}
