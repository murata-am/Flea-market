<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;


class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $sessionAddress = [
            'postal_code' => '111-2222',
            'address' => '東京都新宿区テスト町1-2-3',
            'building' => 'テストビル501',
        ];

        $response = $this
            ->withSession(['shipping_address' => $sessionAddress])
            ->get(route('purchase.index', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('〒 ' . $sessionAddress['postal_code']);
        $response->assertSee($sessionAddress['address']);
        $response->assertSee($sessionAddress['building']);
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create();

        $sessionAddress = [
            'postal_code' => '333-4444',
            'address' => '大阪府テスト市4-5-6',
            'building' => 'サンプルハイツ202',
        ];

        $response = $this
            ->withSession(['shipping_address' => $sessionAddress])
            ->actingAs($user)
            ->post(route('purchase.store', ['item_id' => $item->id]), [
                'payment_method' => 'カード支払い',
            ]);

        $response->assertRedirect(route('mypage', ['tab' => 'buy']));
        $this->assertDatabaseHas('sold_items', [
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => '〒' . $sessionAddress['postal_code'] . ' ' . $sessionAddress['address'] . ' ' . $sessionAddress['building'],
            'payment_method' => 'カード支払い',
        ]);

    }
}
