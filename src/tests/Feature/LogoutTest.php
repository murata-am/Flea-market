<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    public function test_ログアウトができる()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
