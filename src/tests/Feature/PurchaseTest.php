<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_「購入する」ボタンを押下すると購入が完了する()
    {
        $user = User::factory()->create();
        $user->profile()->create([
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'サンプルマンション101',
        ]);

        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
        ]);

        $response->assertRedirect(route('mypage', ['tab' => 'buy']));
        $response->assertSessionHas('success', '購入が完了しました！');

        $expectedAddress = '〒123-4567 東京都渋谷区1-2-3 サンプルマンション101';

        $this->assertDatabaseHas('sold_items', [
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => $expectedAddress,
            'payment_method' => 'コンビニ払い',
        ]);

    }

    public function test_購入した商品は商品一覧にて「Sold」と表示される()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertSee('sold');
    }

    public function test_購入した商品は「プロフィール・購入した商品一覧」に追加されている()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create();

        $this->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'カード支払い',
        ]);

        $response = $this->actingAs($user)->get(route('mypage', ['tab' => 'buy']));

        $response->assertSee($item->name);
        $response->assertSee($item->image);

    }
}
