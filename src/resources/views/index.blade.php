@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

    <div class="tab">
        <a href="/" class="{{ !request('tab') || request('tab') === 'all' ? 'active' : '' }}">おすすめ</a>
        <a href="/?tab=mylist" class="{{ request('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="container">

        @if(isset($items) && count($items) > 0)
            @foreach($items as $item)
                <div class="item">
                    <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-link">
                        <div class="item-image-wrapper" style="position: relative;">
                            <img class="item_image" src="{{ asset($item->image) }}" alt="{{ $item->name }}">
                            @if($item->is_sold)
                                <div class="sold-label">
                                    <span class="sold-text">Sold</span>
                                </div>
                            @endif
                        </div>
                        <p class="image_name">{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        @else
            <p>商品がありません。</p>
        @endif
    </div>
@endsection