<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\LikeButton;

class LikeButtonTest extends TestCase
{
    use RefreshDatabase;
    public function test_いいねアイコンを押下することで、いいねした商品として登録することができる()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        $response = $this->post(route('like.toggle', ['item' => $item->id]));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'liked',
                'like_count' => 1,
            ]);

        $this->assertDatabaseHas('like_buttons', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    public function test_追加済みのアイコンは色が変化する()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        LikeButton::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200)
            ->assertSee('⭐');
    }

    public function test_再度いいねをしている商品を押下すると、いいねを解除される()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();

        LikeButton::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->post(route('like.toggle', ['item' => $item->id]));

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'unliked',
                'like_count' => 0,
        ]);

        $this->assertDatabaseMissing('like_buttons', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertSee('☆');
    }
}



