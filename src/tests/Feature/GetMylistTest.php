<?php

namespace Tests\Feature;

use App\Models\LikeButton;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetMylistTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_マイリストにはいいねした商品だけが表示される()
    {
        $user = User::factory()->create();

        $LikedItem = Item::factory()->create([
            'name' => 'いいねした商品',
            'image' => 'storage/items/bag.jpg'
        ]);
        $notLikedItem = Item::factory()->create([
            'name' => 'いいねしていない商品',
            'image' => 'storage/items/HDD.jpg'
        ]);

        LikeButton::create([
            'user_id' => $user->id,
            'item_id' => $LikedItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertSee('storage/items/bag.jpg');
        $response->assertDontSee('いいねしていない商品');
        $response->assertDontSee('storage/items/HDD.jpg');
    }

    /** @test */
    public function 購入済み商品にはSoldラベルが表示される()
    {
        $user = User::factory()->create();
        $soldItem = Item::factory()->create();

        LikeButton::create([
            'user_id' => $user->id,
            'item_id' => $soldItem->id,
        ]);

        SoldItem::create([
            'item_id' => $soldItem->id,
            'buyer_id' => $user->id,
            'shipping_address' => '東京都渋谷区1-2-3',
            'payment_method' => 'コンビニ払い'
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品はマイリストに表示されない()
    {
        $user = User::factory()->create();
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '商品A',
            'image' => 'storage/items/bag.jpg'
        ]);
        $otherItem = Item::factory()->create([
            'name' => '商品B',
            'image' => 'storage/items/HDD.jpg'
        ]);

        LikeButton::create([
            'user_id' => $user->id,
            'item_id' => $myItem->id,
        ]);
        LikeButton::create([
            'user_id' => $user->id,
            'item_id' => $otherItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('商品B');
        $response->assertSee('storage/items/HDD.jpg');
        $response->assertDontSee('商品A');
        $response->assertDontSee('storage/items/bag.jpg');
    }
}
