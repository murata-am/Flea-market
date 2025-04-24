<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfileFactory extends Factory
{
    protected $model = Profile::class;
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'image' => 'images/default.jpeg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
            'building' => 'テストアパート101'
        ];
    }
}
