<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function edit()
    {
        return view('mypage.profile');
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $user->update([
        'name' => $request->name,
        'postal_code' => $request->postal_code,
        'address' => $request->address,
        'building' => $request->building,
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images', 'public'); // storage/app/public/profile_images に保存

            $user->image = $path;
        }

        $user->save();

        return redirect()->route('mylist')->with('success', 'プロフィールを更新しました');
    }



    public function showUser()
    {
        return view('mypage.index', ['user' => Auth::user()]);
    }

}
