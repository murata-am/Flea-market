<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use Database\Seeders\CategorySeeder;
use App\Models\LikeButton;


class ShowItemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CategorySeeder::class);
    }


    public function test_すべての情報が商品詳細ページに表示されている()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);

        $user->profile()->create([
            'image' => 'storage/items/watch.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都テスト区テスト町1-2-3',
        ]);

        $this->actingAs($user);

        $item = Item::factory()->create([
            'user_id' => $user->id,
            'brand_name' => 'ユニクロ',
            'name' => 'シャツ',
            'price' => 2990,
            'description' => '着心地の良いシャツです。',
            'condition' => '1',
            'image' => 'storage/items/watch.jpg',
        ]);

        LikeButton::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $item->like_count = LikeButton::where('item_id', $item->id)->count();

        $categories = Category::inRandomOrder()->take(rand(1, 3))->get();
        $item->categories()->attach($categories->pluck('id'));

        $comments = Comment::factory()->count(2)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);


        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200)
            ->assertSee($item->name)
            ->assertSee($item->brand_name)
            ->assertSee(number_format($item->price))
            ->assertSee($item->description)
            ->assertSee('良好')
            ->assertSee((string) $item->like_count)
            ->assertSee('storage/items/watch.jpg',);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }

        foreach ($comments as $comment) {
            $response->assertSee($comment->content);
            $response->assertSee($comment->user->name);
            }
    }


    public function test_複数選択されたカテゴリが表示されている()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $user->id]);

        $categories = Category::inRandomOrder()->take(3)->get();
        $item->categories()->attach($categories->pluck('id'));

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }

    }
}
