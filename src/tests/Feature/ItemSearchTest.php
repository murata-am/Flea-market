<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemSearchTest extends TestCase
{

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $user = User::factory()->create();

        Item::factory()->create([
            'name' => '赤いバッグ',
            'image' => 'storage/items/bag.jpg'
        ]);
        Item::factory()->create([
            'name' => '黒いHDD',
            'image' => 'storage/items/HDD.jpg'
        ]);


        $response = $this->actingAs($user)->get('/search?query=赤い');

        $response->assertStatus(200);
        $response->assertSee('赤いバッグ');
        $response->assertSee('storage/items/bag.jpg');
        $response->assertDontSee('黒いHDD');
        $response->assertDontSee('storage/items/HDD.jpg');
    }

    /** @test */
    public function マイリストに遷移しても検索キーワードが保持されている()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/?tab=mylist&query=赤い');

        $response->assertStatus(200);
        $response->assertSee('value="赤い"', false);
    }

}
