@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2>商品一覧</h2>

        <!-- タブメニュー -->
        <a href="/" class="{{ request('/') ? 'active' : '' }}">すべての商品</a>
        <a href="/mylist" class="{{ request()->is('mylist') ? 'active' : '' }}">マイリスト</a>

        <!-- 商品リスト表示 -->
        @if(isset($Items) && count($Items) > 0)
            @foreach($Items as $item)
                <p>{{ $item->name }}</p>
            @endforeach
        @else
            <p>商品がありません。</p>
        @endif
    </div>
@endsection