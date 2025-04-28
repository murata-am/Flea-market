<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use App\Models\SoldItem;

class GetUserTest extends TestCase
{
    use RefreshDatabase;
    public function test_マイページ画面で必要な情報が取得できる()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]);
        $profile = Profile::factory()
        ->state(['user_id' => $user->id])
        ->create();

        $sellItems = Item::factory()->count(2)->create([
            'user_id' => $user->id,
            'name' => '出品商品',
            'image' => 'storage/items/bag.jpg'
        ]);

        $seller = User::factory()->create();
        $soldItems = Item::factory()->count(2)->create([
            'user_id' => $seller->id,
            'name' => '購入商品',
            'image' => 'storage/items/shoes.jpg'
        ]);

        foreach ($soldItems as $item) {
            SoldItem::factory()->create([
                'item_id' => $item->id,
                'buyer_id' => $user->id,
                'shipping_address' => '東京都渋谷区',
            ]);
        }

        $this->actingAs($user);

        $responseSell = $this->get('/mypage/?tab=sell');
        $responseSell->assertStatus(200);
        $responseSell->assertSee('テストユーザー');
        $responseSell->assertSee('storage/items/bag.jpg');

        foreach ($sellItems as $item) {
            $responseSell->assertSee($item->name);
            $responseSell->assertSee($item->image);
        }

        $responseBuy = $this->get('/mypage/?tab=buy');
        $responseBuy->assertStatus(200);
        foreach ($soldItems as $item) {
            $responseBuy->assertSee($item->name);
            $responseBuy->assertSee($item->image);
        }
    }
}