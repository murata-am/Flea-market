@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage-index.css') }}">
@endsection

@section('content')
    <div class="profile_container">
        <img class="profile_image" src="{{ isset($profile) && $profile->image ? asset('storage/' . $profile->image) : asset('images/default.png') }}" alt="">

        <h2 class="user_name" id="name">{{ $user->name }}</h2>

        <a class="profile_edit" href="/mypage/profile">プロフィールを編集</a>
    </div>

    <div class="tab">
        <a href="{{ route('mypage', ['tab' => 'sell']) }}" class="{{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ route('mypage', ['tab' => 'buy']) }}" class="{{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <div class="item_container">

        @if(isset($items) && count($items) > 0)
            @foreach($items as $item)
                <div class="item">
                    <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                    <p class="image_name">{{ $item->name }}</p>
                </div>
            @endforeach
        @else
            <p>商品がありません。</p>
        @endif
    </div>

@endsection