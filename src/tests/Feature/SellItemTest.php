<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SellItemTest extends TestCase
{
    use RefreshDatabase;
    public function test_商品出品画面にて必要な情報が保存できる()
    {
        $this->seed(\Database\Seeders\CategorySeeder::class);

        Storage::fake('public');

        $user = User::factory()->create();
        $categories = Category::inRandomOrder()->take(3)->get();

        $fakeImage = UploadedFile::fake()->image('bag.jpg');

        $this->actingAs($user);

        $response = $this->post('/sell', [
            'name' => 'テスト商品',
            'description' => 'テスト用の説明です',
            'condition' => '1',
            'price' => 3000,
            'image' => $fakeImage,
            'category_ids' => $categories->pluck('id')->implode(','),
        ]);

        $response = $this->get(route('mypage', ['tab' => 'sell']));

        $response->assertSee('テスト商品');
        $item = Item::where('name', 'テスト商品')->first();
        $this->assertNotNull($item);
        $this->assertStringContainsString('items/', $item->image);
        $response->assertSee($item->image);

        $this->assertDatabaseHas('items', [
            'name' => 'テスト商品',
            'description' => 'テスト用の説明です',
            'condition' => '1',
            'price' => 3000,
            'image' => $item->image,
        ]);

        foreach ($categories as $category) {
            $this->assertDatabaseHas('item_category', [
                'item_id' => $item->id,
                'category_id' => $category->id,
            ]);
        }

    }
}
