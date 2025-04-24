<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class PaymentSelectTest extends TestCase
{
    use RefreshDatabase;
    public function test_小計画面で選択した支払方法が正しく反映されている()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'price' => 5000,
            'image' => 'test.jpg',
            'name' => 'テスト商品',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('purchase.store', ['item_id' => $item->id]), [
            'payment_method' => 'カード支払い',
        ]);

        $response->assertRedirect(route('mypage', ['tab' => 'buy']));

        $this->assertDatabaseHas('sold_items', [
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
        ]);

        $response = $this->get(route('purchase.index', ['item_id' => $item->id]));
        $response->assertSee('カード支払い');
    }
}
