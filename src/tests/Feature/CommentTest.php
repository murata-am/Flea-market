<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use App\Models\Item;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/default.jpeg',
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson("/item/{$item->id}/comment", [
            'content' => 'これはテストコメントです',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです',
        ]);
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comment", [
            'content' => '未ログインのコメント',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseMissing('comments', [
            'content' => '未ログインのコメント',
        ]);
    }

    public function test_コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/default.jpeg',
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->postJson("/item/{$item->id}/comment", [
            'content' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content'])
            ->assertJsonFragment([
                'content' => ['コメントを入力してください'],
            ]);

    }

    public function test_コメントが255文字以上の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create();
        Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'images/default.jpeg',
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longContent = str_repeat('あ', 256);
        $response = $this->postJson("/item/{$item->id}/comment", [
            'content' => $longContent,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content'])
            ->assertJsonFragment([
                'content' => ['コメントは255文字以内で入力してください'],
            ]);

    }
}
