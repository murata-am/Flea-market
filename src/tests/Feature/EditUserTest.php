<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;


class EditUserTest extends TestCase
{
    use RefreshDatabase;
    public function test_変更項目が初期値として過去設定されている()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
        ]
        );
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'image' => 'storage/images/default.jpeg',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストアパート101'
        ]);

        $this->actingAs($user);
        $response = $this->get('/mypage/profile');

        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('storage/images/default.jpeg');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区');
        $response->assertSee('テストアパート101');

    }
}
