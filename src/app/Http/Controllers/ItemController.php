<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\ExhibitionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Item;
use App\Models\SoldItem;
use App\Models\Category;
use App\Models\User;
use App\Models\LikeButton;
use App\Models\Comment;




class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'all'); // クエリパラメータ `tab` を取得（デフォルトは 'all'）

        if ($tab === 'mylist') {
            if($user) {
                $items = Item::whereHas('likes', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
            })
            ->where('user_id', '!=', $user->id)
            ->get();
            } else {
                $items = collect();
            }
        } else {
            if ($user) {
                $items = Item::where('user_id', '!=', $user->id)->get();
            } else {
                $items = Item::all();
            }
        }

        $soldItemIds = SoldItem::pluck('item_id')->toArray();

        foreach ($items as $item) {
            $item->is_sold = in_array($item->id, $soldItemIds);
        }

        return view('index', compact('items'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $items = Item::where('name', 'like', '%' . $query . '%')->get();

        return view('index', ['items' => $items]);
    }

    public function showItem($item_id)
    {
        $item = Item::with(['categories', 'comments'])->withCount('comments')->findOrFail($item_id);

        $soldItem= SoldItem::where('item_id', $item->id)->exists();  // 購入履歴があるか確認

        $user = auth()->user();
        $item_likes = LikeButton::where('item_id', $item->id)->count();

        $my_like_check = $user ? LikeButton::where('user_id', $user->id)->where('item_id', $item->id)->exists() : false;
        $item->like_count = $item_likes;

        $comments = Comment::where('item_id', $item_id)
            ->with('user') // コメントしたユーザー情報
            ->orderBy('created_at', 'desc')
            ->get();

        $comments_count = $item->comments()->count();

        return view('show', compact('item', 'my_like_check', 'item_likes', 'comments', 'soldItem'))
            ->with('categories', $item->categories)
            ->with('comments_count', $item->comments_count)
            ->with('user_id', $user ? $user->id : null)
            ->with('axios_path', route('like.toggle', ['item' => $item->id]));
    }

    public function toggle(Item $item)
    {
        $user = Auth::user();

        // すでに「いいね」しているかチェック
        $like = LikeButton::where('user_id', $user->id)->where('item_id', $item->id)->first();

        if ($like) {
            $like->delete();// いいねを削除
            $status = "unliked";
        } else {
            // いいねを追加
            LikeButton::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
            $status = "liked";
        }
            $like_count = LikeButton::where('item_id', $item->id)->count();

            return response()->json([
            'status' => $status,
            'like_count' => $like_count
        ]);
    }

    public function store(CommentRequest $request, $item_id)
    {
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
            'content' => $request->content,
        ]);

        $comment = Comment::with('user.profile')->find($comment->id);


        return response()->json([
            'comment' => [
                'content' => $comment->content,
                'user' => [
                    'name' => $comment->user->name,
                    'profile_image' => $comment->user->profile->image && $comment->user->profile->image
                        ? asset('storage/' . $comment->user->profile->image)
                        : asset('default-profile.png'),
                ],
            ]
        ], 201, [], JSON_UNESCAPED_UNICODE);

    }

    public function purchase($item_id)
    {
        $user = Auth::user();
        $item = Item::find($item_id);
        $profile = $user->profile;

        $shippingAddressFromSession = session()->get('shipping_address');

        $shippingAddressToShow = $shippingAddressFromSession ?? [
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ];

        return view('purchase.index', compact('item', 'profile', 'shippingAddressToShow'));
    }

    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $item = Item::find($item_id);

        if (!$item) {
            return back()->withErrors(['item' => '商品が見つかりません']);
        }

        $shipping_address = session()->get('shipping_address', [
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ]);

        if (empty($shipping_address['postal_code']) || empty($shipping_address['address'])) {
            return back()->withErrors(['shipping_address' => '配送先を変更してください']);
        }

        $request->merge([
            'shipping_address' => $shipping_address['postal_code'] . $shipping_address['address']
        ]);


        SoldItem::create([
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'shipping_address' => '〒' . $shipping_address['postal_code'] . ' ' . $shipping_address['address'] . ' ' . ($shipping_address['building'] ?? ''),
            'payment_method' => $request->payment_method,
        ]);


        return redirect()->route('mypage')->with('success', '購入が完了しました！');
    }


    public function create()
    {
        session()->forget('image');
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function sellStore(ExhibitionRequest $request)
    {
        if ($request->hasFile('image')) {
            // 一旦保存（バリデーション前でも保存してOKです）
            $storedPath = $request->file('image')->store('items', 'public');
            $imagePath = 'storage/' . $storedPath;

            session()->flash('image_path', $imagePath);

            } else {
                return redirect()->back()->withErrors(['image' => '画像は必須です。'])->withInput();
            }


        $item = Item::create([
            'user_id'       => auth()->id(),
            'name'          => $request->name,
            'brand_name'    => $request->brand,
            'description'   => $request->description,
            'price'         => $request->price,
            'condition'     => $request->condition,
            'image'         => $imagePath,
        ]);

        session()->forget('image_path');

        if ($request->has('category_ids')) {
            $categoryIds = explode(',', $request->category_ids);
            $item->categories()->attach($categoryIds);
        }



        return redirect()->route('mypage')->with('success', '商品が出品されました！');
    }

}