<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        Item::factory()->create([
            'name' => '商品A',
            'user_id' => User::factory()->create()->id,
            'image' => 'storage/items/bag.jpg',

        ]);
        Item::factory()->create([
            'name' => '商品B',
            'user_id' => User::factory()->create()->id,
            'image' => 'storage/items/HDD.jpg',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('商品A');
        $response->assertSee('商品B');

        $response->assertSee('storage/items/bag.jpg');
        $response->assertSee('storage/items/HDD.jpg');
    }

    public function test_購入済み商品は「Sold」と表示される()
    {
        $user = User::factory()->create();

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'テスト商品',
            'image' => 'storage/items/bag.jpg',
        ]);

        SoldItem::factory()->create([
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => '123-4567 東京都新宿区',
            'payment_method' => 'コンビニ払い',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();
        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の出品した商品',
            'image' => 'storage/items/bag.jpg',
        ]);

        $otherUser = User::factory()->create();
        Item::factory()->create([
            'user_id' => $otherUser->id,
            'name' => '他人の商品',
            'image' => 'storage/items/HDD.jpg',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の出品した商品');
        $response->assertSee('他人の商品');


    }

}
