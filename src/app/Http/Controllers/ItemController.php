<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        return view('index');
    }

    // **ログイン済みユーザーの「マイリスト」ページ**
    public function mylist()
    {
        // **ログインユーザーがお気に入りした商品を取得**

        return view('index');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');  // 検索キーワードを取得

        // 商品名に部分一致するアイテムを取得
        $items = Item::where('name', 'like', '%' . $query . '%')->get();

        // 検索結果をビューに渡す
        return view('index', ['items' => $items]);
    }


}
