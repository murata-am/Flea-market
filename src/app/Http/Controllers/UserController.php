<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class UserController extends Controller
{

    public function edit(Request $request)
    {
        $user = auth()->user();
        $profile = Profile::where('user_id', $user->id)->first();

        if (!$profile) {
            session(['from_register' => true]);

            $profile = new Profile();
            $profile->user_id = $user->id;
        }

        return view('mypage.profile', compact('user', 'profile'));
    }

    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest): RedirectResponse
    {
        $validatedProfile = $profileRequest->validated();
        $validatedAddress = $addressRequest->validated();

        $user = Auth::user();
        $profile = Profile::firstOrNew(['user_id' => $user->id]);

        $profile->postal_code = $validatedAddress['postal_code'];
        $profile->address = $validatedAddress['address'];
        $profile->building = $validatedAddress['building'] ?? '';


        // 画像アップロード処理
        if ($profileRequest->hasFile('image')) {
            $file = $profileRequest->file('image');
            $path = $file->store('images', 'public');
            $profile->image = $path;
        }

        $profile->save();

        $profileImageUrl = asset('storage/' . $profile->image);

        if (session()->pull('from_register')) {
            // 初回登録時：商品一覧ページへ
            return redirect()->route('items.index', ['tab' => 'mylist'])
                ->with('success', 'プロフィールを更新しました');
        } else {
            // 通常のプロフィール更新時：マイページへ
            return redirect()->route('mypage')
                ->with('success', 'プロフィールを更新しました');
        }
    }



    public function showUser(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
        }

        $tab = $request->query('tab', 'sell');

        if ($tab === 'buy') {
            $soldItems = $user->soldItems()->with('item')->get();
            $items = $soldItems->pluck('item');
        } else {
            $items = $user->items;
        }
        return view('mypage.index', compact('user', 'profile', 'items', 'tab'));
    }

    public function editAddress($item_id)
    {
        $profile = auth()->user()->profile;


        return view('purchase.address', [
            'profile' => $profile,
            'item_id' => $item_id,
        ]);

    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $shippingAddress = [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];

        session()->put('shipping_address', $shippingAddress);

        $user = Auth::user();
        $item = Item::find($item_id);
        $profile = $user->profile;

        $shippingAddressFromSession = session()->get('shipping_address');
        $shippingAddressToShow = $shippingAddressFromSession ?? [
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
        ];

        return redirect()->route('purchase.index', ['item_id' => $item_id])
            ->with('success', '住所が更新されました');
    }
}