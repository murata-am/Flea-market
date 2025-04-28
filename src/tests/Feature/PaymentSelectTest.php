<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;
use Livewire\Livewire;
use App\Models\SoldItem;

class PaymentSelectTest extends TestCase
{
    use DatabaseTransactions;
    public function test_小計画面で選択した支払方法が正しく反映されている()
    {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
        ]);
        $item = Item::factory()->create([
            'price' => 5000,
            'image' => 'test.jpg',
            'name' => 'テスト商品',
        ]);

        $this->actingAs($user);

        Livewire::test('purchase-form', [
            'item' => $item,
            'profile' => $profile,
            'shippingAddress' => [
                'postal_code' => '123-4567',
                'address' => '東京都渋谷区1-2-3',
                'building' => 'テストビル101',
            ],
        ])

            ->set('payment_method', 'カード支払い')
            ->assertSee('カード支払い')
            ->call('submit')
            ->assertRedirect(route('mypage', ['tab' => 'buy']));
    }
}
