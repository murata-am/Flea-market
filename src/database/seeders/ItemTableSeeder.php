<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::firstOrCreate([
            'email' => 'user1@example.com', // 一意のメールアドレスを使う
            ],
            ['name' => 'ユーザー1',
            'password' => bcrypt('password'),
            ]);

        $user2 = User::firstOrCreate([
            'email' => 'user2@example.com',
        ], [
            'name' => 'ユーザー2',
            'password' => bcrypt('password'),
        ]);

        $items = [
            [
                'user_id' => $user1->id,
                'name' => '腕時計',
                'price'  => 15000,
                'categories' => ['ファッション', 'メンズ'],
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'condition' => 1, //良好
                'image' => 'storage/items/watch.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id,
                'name' => 'HDD',
                'price' => 5000,
                'categories' => ['家電'],
                'description' => '高速で信頼性の高いハードディスク',
                'condition' => 2, //目立った傷や汚れなし
                'image' => 'storage/items/HDD.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user1->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'categories' => ['キッチン'],
                'description' => '新鮮な玉ねぎ3束のセット',
                'condition' => 3, //やや傷や汚れあり
                'image' => 'storage/items/onion.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user1->id,
                'name' => '革靴',
                'price' => 4000,
                'categories' => ['ファッション', 'メンズ'],
                'description' => 'クラシックなデザインの革靴',
                'condition' => 4, //状態が悪い
                'image' => 'storage/items/shoes.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user1->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'categories' => ['家電', 'ゲーム'],
                'description' => '高性能なノートパソコン',
                'condition' => 1, //良好
                'image' => 'storage/items/Laptop.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user2->id,
                'name' => 'マイク',
                'price' => 8000,
                'categories' => ['家電', 'おもちゃ'],
                'description' => '高品質のレコーディング用マイク',
                'condition' => 2, //目立った傷や汚れなし
                'image' => 'storage/items/mic.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user2->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'categories' => ['ファッション', 'レディース'],
                'description' => 'おしゃれなショルダーバッグ',
                'condition' => 3, //やや傷や汚れあり
                'image' => 'storage/items/bag.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user2->id,
                'name' => 'タンブラー',
                'price' => 500,
                'categories' => ['キッチン', '家電'],
                'description' => '使いやすいタンブラー',
                'condition' => 4, //状態が悪い
                'image' => 'storage/items/tumbler.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user2->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'categories' => ['家電', 'キッチン'],
                'description' => '手動のコーヒーミル',
                'condition' => 1, //良好
                'image' => 'storage/items/CoffeeGrinder.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),

            ],
            [
                'user_id' => $user2->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'categories' => ['レディース', 'アクセサリー'],
                'description' => '便利なメイクアップセット',
                'condition' => 2, //目立った傷や汚れなし
                'image' => 'storage/items/makeup-set.jpg',
                'brand_name' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ];

        foreach ($items as $data) {
            // アイテムを作成
            $item = Item::create([
                'user_id' => $data['user_id'],
                'name' => $data['name'],
                'price' => $data['price'],
                'description' => $data['description'],
                'condition' => $data['condition'],
                'image' => $data['image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($data['categories'] as $categoryName) {
                $category = Category::where('name', $categoryName)->first();
                if ($category) {
                    $item->categories()->attach($category->id);
                }
            }
        }
    }
}
